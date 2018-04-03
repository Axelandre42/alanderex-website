<?php
namespace App\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestVoter implements VoterInterface
{
    public function matchItem(ItemInterface $item)
    {
        $request = Request::createFromGlobals();
    	if ($item->getUri() === $request->getRequestUri()) {
    		// URL's completely match
            return true;
        } else if($item->getUri() !== $request->getBaseUrl().'/' && (substr($request->getRequestUri(), 0, strlen($item->getUri())) === $item->getUri())) {
        	// URL isn't just "/" and the first part of the URL match
	    	return true;
    	}
        return null;
    }
}