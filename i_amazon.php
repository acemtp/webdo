<?

function amazon_normalise_url($url) {
	if ($url == '') return '';
	if (!preg_match('/^https?:\/\//i', $url)) {
		return 'http://'.$url;
	}
	return $url;
}

function amazon_est_url($url) {
	$host = parse_url($url, PHP_URL_HOST);
	if (!$host) return false;
	return preg_match('/(^|\.)amazon\.[a-z.]+$/i', $host) === 1;
}

function amazon_titre_auto($url) {
	if (!amazon_est_url($url)) return '';

	$path = parse_url($url, PHP_URL_PATH);
	if ($path && preg_match('#/(dp|gp/product)/([A-Z0-9]{10})#i', $path, $matches)) {
		return 'Cadeau Amazon '.$matches[2];
	}

	return 'Cadeau Amazon';
}

function amazon_fetch_product_data($url) {
	$result = array(
		'titre' => '',
		'image' => '',
	);

	if (!amazon_est_url($url)) {
		return $result;
	}

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0 Safari/537.36');
	$html = curl_exec($ch);

	if (!is_string($html) || $html == '') {
		return $result;
	}

	libxml_use_internal_errors(true);
	$dom = new DOMDocument();
	if (!@$dom->loadHTML($html)) {
		return $result;
	}

	$xpath = new DOMXPath($dom);

	$result['titre'] = amazon_find_meta_content($xpath, 'og:title');
	if ($result['titre'] == '') {
		$result['titre'] = amazon_find_meta_content($xpath, 'twitter:title');
	}
	if ($result['titre'] == '') {
		$titleNodes = $dom->getElementsByTagName('title');
		if ($titleNodes->length > 0) {
			$result['titre'] = trim(html_entity_decode($titleNodes->item(0)->textContent, ENT_QUOTES, 'UTF-8'));
		}
	}

	$result['image'] = amazon_find_product_image($xpath);

	$result['titre'] = amazon_cleanup_title($result['titre'], $url);
	$result['image'] = trim($result['image']);

	return $result;
}

function amazon_find_meta_content($xpath, $property) {
	$nodes = $xpath->query('//meta[@property="'.$property.'"]/@content | //meta[@name="'.$property.'"]/@content');
	if ($nodes->length === 0) return '';
	return trim(html_entity_decode($nodes->item(0)->nodeValue, ENT_QUOTES, 'UTF-8'));
}

function amazon_cleanup_title($title, $url) {
	$title = trim($title);
	if ($title == '') {
		return amazon_titre_auto($url);
	}

	$title = preg_replace('/\s*:\s*Amazon\..*$/i', '', $title);
	$title = preg_replace('/\s*-\s*Amazon\..*$/i', '', $title);
	$title = preg_replace('/\s*-\s*Amazon(?:\s.+)?$/i', '', $title);
	$title = trim($title);

	if ($title == '') {
		return amazon_titre_auto($url);
	}

	return $title;
}

function amazon_find_product_image($xpath) {
	$candidates = array();

	// Amazon product page main image hooks first.
	$candidates[] = amazon_xpath_first_attr($xpath, '//*[@id="landingImage"]/@src');
	$candidates[] = amazon_xpath_first_attr($xpath, '//*[@id="landingImage"]/@data-old-hires');
	$candidates[] = amazon_xpath_first_attr($xpath, '//*[@id="imgBlkFront"]/@src');
	$candidates[] = amazon_xpath_first_attr($xpath, '//*[@id="imgBlkFront"]/@data-old-hires');
	$candidates[] = amazon_xpath_first_attr($xpath, '//*[@id="main-image-container"]//img/@src');
	$candidates[] = amazon_xpath_first_attr($xpath, '//meta[@property="og:image"]/@content');
	$candidates[] = amazon_xpath_first_attr($xpath, '//meta[@name="twitter:image"]/@content');

	$imageNodes = $xpath->query('//img[@src]');
	foreach ($imageNodes as $imageNode) {
		$candidates[] = trim($imageNode->getAttribute('data-old-hires'));
		$candidates[] = trim($imageNode->getAttribute('src'));
	}

	foreach ($candidates as $candidate) {
		if (amazon_is_good_product_image($candidate)) {
			return $candidate;
		}
	}

	return '';
}

function amazon_xpath_first_attr($xpath, $query) {
	$nodes = $xpath->query($query);
	if ($nodes->length === 0) return '';
	return trim($nodes->item(0)->nodeValue);
}

function amazon_is_good_product_image($url) {
	if (!is_string($url) || $url === '') return false;
	if (!preg_match('/^https?:\/\//i', $url)) return false;

	$host = parse_url($url, PHP_URL_HOST);
	if (!$host) return false;

	$path = parse_url($url, PHP_URL_PATH);
	$lower = strtolower($url);

	if (strpos($host, 'm.media-amazon.com') === false && strpos($host, 'images-amazon.com') === false) {
		return false;
	}

	if (strpos($lower, 'nav-sprite') !== false) return false;
	if (strpos($lower, 'sprite') !== false) return false;
	if (strpos($lower, 'icon') !== false) return false;
	if (strpos($lower, 'logo') !== false) return false;
	if (strpos($lower, 'transparent-pixel') !== false) return false;
	if (strpos($lower, 'loadindicator') !== false) return false;

	if ($path && preg_match('/\.(png|gif)$/i', $path) && strpos($lower, '/images/i/') === false) {
		return false;
	}

	return true;
}

?>
