<?php

namespace App\Http\Controllers;

use App\Item;
use App\WPModels\WPProduct;
use App\WPModels\WPAttribute;
use App\WPModels\WPItemCategory;
use App\WPModels\WPAttributeValue;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use GuzzleHttp\Client;


class ItemController extends Controller
{
    protected $client;
    protected $uploadsPath;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->uploadsPath = 'uploads';
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $wpItemCategory = new WPItemCategory($this->client, 'categories');
        $wpItemAttribute = new WPAttribute($this->client, 'attributes');
        $wpAttributeValue = new WPAttributeValue($this->client, 'terms');
        $attributes = $wpItemAttribute->all();
        $categories = $wpItemCategory->all();
        foreach ($attributes as $key => $attribute) {
            $attributes[$key]->value = $wpAttributeValue->getByAttributeID( $attribute->id );

        }

        return view('item/create',[
            'attributes'      => $attributes,
            'categories'      => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'price'=> 'required|regex:/^\d+(\.\d{1,2})?$/',
            'file' => 'mimes:jpeg,jpg,png',
            'width' => 'integer',
            'height' => 'integer',
            'length' => 'integer',
            //'category' => 'required',
            //'attribute.*' => 'required',
        ]);

        $newItem = [
            'name'=>$request->name,
            'regular_price'=>$request->price,
            'status'=> 'draft',
            'dimensions'=>[
                'length'=>$request->length,
                'height'=>$request->height,
                'width'=>$request->width,
            ]
        ];

        if (isset($request->category) && count($request->category) > 0) {
            foreach ($request->category as $key => $value) {
                if ($value === 'on') {
                    $newItem['categories'][] = ['id' => $key];
                }
            }
        }

        if (isset($request->attribute) && count($request->attribute) > 0) {
            foreach ($request->attribute as $key => $value) {
                $newItem['attributes'][] = ['id' => $key , 'options' => $value];
            }
        }
        

        $WPProduct = new WPProduct($this->client, '');

        $url = ($request->hasFile('file')) ? $this->uploadFile($request) : '';
        $uploadRes = $WPProduct->upload($url);
        if (isset($uploadRes->guid->raw)) {
            $newItem['images'][] = [ 'src' => $uploadRes->guid->raw ];
        }

        print_r($newItem);
        $result = $WPProduct->save($newItem);
        if (isset($result->id)) {
            return redirect('home')->with('statusSuccess', 'Item Added!');
        }
        return redirect('home')->with('statusError', 'Item Not Added!');
        
        // $wpItemCategory = new WPItemCategory($this->client, 'categories');
        // $wpItemAttribute = new WPAttribute($this->client, 'attributes');
        // $wpAttributeValue = new WPAttributeValue($this->client, 'terms');
        // $attributes = $wpItemAttribute->all();
        // $categories = $wpItemCategory->all();
        // foreach ($attributes as $key => $attribute) {
        //     $attributes[$key]->value = $wpAttributeValue->getByAttributeID( $attribute->id );
        // }
        // return view('item/create',[
        //     'attributes'      => $attributes,
        //     'categories'      => $categories,
        // ]);

    }

    private function uploadFile(Request $request)
    {
        $file = $request->file('file');
 
        if (!is_dir( public_path($this->uploadsPath)) ) {
            mkdir(public_path($this->uploadsPath), 0777);
        }
        $dt = Carbon::now();
        $name  =$dt->format('Y-m-d-h-i') . '_'  . $request->file('file')->getClientOriginalName();
        $res = Storage::disk('public_path')->put( $this->uploadsPath . '/' . $name , file_get_contents($file) );
        if ( $res ) {
            $fullPath = Storage::disk('public_path')->getDriver()->getAdapter()->getPathPrefix() . $this->uploadsPath . '/' . $name ;
            return Storage::disk('public_path')->getDriver()->getAdapter()->getPathPrefix() . $this->uploadsPath . '/' . $name;
        }
        else {
            return response()->json(['status' => 'error', 'message' => 'Файл не загружен. Попробуйте снова.'], 400);
        }
    }
}
