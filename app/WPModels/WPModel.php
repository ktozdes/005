<?php 
namespace App\WPModels;

use GuzzleHttp\Client;

abstract class WPModel
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $client;

    /**
     * The post associated with the model.
     *
     * @var string
     */
    protected $subUrl;

    public function __construct(Client $client, string $subUrl = '')
    {
    	$this->subUrl = $subUrl;
        $this->client = $client;
    }

    public function all()
    {
    	try {
            $response = $this->client->request('GET', 'wc/v3/products/' . $this->subUrl);

        } catch (\Exception $e) {
            return [];
        }
        
        return json_decode($response->getBody()->getContents());
    }

    public function getByID($attributeID = 0)
    {
    	try {
            $response = $this->client->request('GET', 'wc/v3/products/' . $this->subUrl . '/' . $attributeID);

        } catch (\Exception $e) {
            return [];
        }
        
        return json_decode($response->getBody()->getContents());
    }


    public function save(array $attributes = [])
    {
    	if (count($attributes) > 0 && is_array($attributes)) {
    		try {
	            $response = $this->client->request('POST', 'wc/v3/products/' . $this->subUrl,[ 
	            	'form_params' => $attributes
	    		]);

	        } catch (\Exception $e) {
	        	echo 'error: '. 'v3/products/' . $this->subUrl;
	            return [];
	        }
	        
	        return json_decode($response->getBody()->getContents());
    	}
    	
    }


    public function upload(string $dir = '')
    {
    	if (strlen($dir) > 1 && file_exists($dir)) {
	    	$file = file_get_contents( $dir );
			$url = env('REMOTE_URL') . 'wp/v2/media';
			$userName =  env('WP_USER');
			$userPass =  env('WP_PASS');
			
			$ch = curl_init();

			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $file );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt( $ch, CURLOPT_HTTPHEADER, [
			    'Content-Disposition: form-data; filename="example.jpg"',
			    'Authorization: Basic ' . base64_encode( $userName . ':' . $userPass ),
			] );
			$result = curl_exec( $ch );
			curl_close( $ch );
			return json_decode( $result );
    	}
    }
}
