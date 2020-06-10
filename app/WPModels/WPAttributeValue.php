<?php 
namespace App\WPModels;

use App\WPModels\WPModel;

class WPAttributeValue extends WPModel
{
	public function getByAttributeID($attributeID = 0)
    {
    	try {
            $response = $this->client->request('GET', 'wc/v3/products/attributes/' . $attributeID . '/' . $this->subUrl);

        } catch (\Exception $e) {
            return [];
        }
        
        return json_decode($response->getBody()->getContents());
    }
}