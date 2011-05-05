<?php
// redirect url
$redirect_url = "http://www3.creazionecoiffure.be/";

/***********************************************************************/
// Vraag toegangscode op voor verschillende soorten toegang
/***********************************************************************/
$url1 = 'https://graph.facebook.com/oauth/authorize?client_id='.FACEBOOK_API_KEY.'&redirect_uri='.$redirect_url.'&scope=email,read_stream,offline_access,manage_pages';
echo $url1.'<br />';
$code = 'zcJseP6qO5zzY5Xx9nbfQkJNuo2NLkRjiWuN7jZECR8.eyJpdiI6IkJtbVk5czQtQXRpMzJJVm1NbG5HOWcifQ.nHExpIY14nOsIl-EmWPj0RMct0tQagtM-WcopAt2yhpXBc_ZkZXeEARDX5aQIapJ_wTFbIxv9W5_KrjU2sfTW10k-KNuCr1cFz2KlMHQIbMjgApvt8K1gzBUiJzEPJtg';


/***********************************************************************/
// Vraag access token op voor deze toegangen
/***********************************************************************/
$url2 = 'https://graph.facebook.com/oauth/access_token?client_id='.FACEBOOK_API_KEY.'&redirect_uri='.$redirect_url.'&client_secret='.FACEBOOK_SECRET.'&code='.$code;
echo $url2.'<br />';
$access_token_step_2 = '225026277511767|84eae1d5e0d165add4cdc702.1-571579524|_IZ8QiV5VlO1ObCHWVT6q490DtY';


/***********************************************************************/
// Vraag token op voor ... ?
/***********************************************************************/
$url3 = 'https://graph.facebook.com/oauth/authorize?client_id='.FACEBOOK_API_KEY.'&redirect_uri='.$redirect_url.'&scope=email,read_stream&response_type=token';
echo $url3.'<br />';
$access_token_step_3 = '225026277511767|84eae1d5e0d165add4cdc702.1-571579524|_IZ8QiV5VlO1ObCHWVT6q490DtY';


/***********************************************************************/
// Vraag token op voor .. ?
/***********************************************************************/
$url4 = 'https://graph.facebook.com/oauth/access_token?client_id='.FACEBOOK_API_KEY.'&client_secret='.FACEBOOK_SECRET.'&grant_type=client_credentials';
echo $url4.'<br />';
$access_token = '225026277511767|W1LyWY4LPoZNz0-PfhR8OoXYqYE';


/***********************************************************************/
// Vraag auth om pages te managen
/***********************************************************************/
$url5 = 'https://graph.facebook.com/oauth/authorize?client_id='.FACEBOOK_API_KEY.'&redirect_uri='.$redirect_url.'&scope=manage_pages&response_type=token';
echo $url5.'<br />';

/***********************************************************************/
// Vraag tot welke pages ik toegang heb
/***********************************************************************/
$url6 ='https://graph.facebook.com/me/accounts?access_token='.$access_token_step_2;
$step_6_token  = '225026277511767|84eae1d5e0d165add4cdc702.1-571579524|198371116865752|6ORY_5TvIPd45bpTT_9IioEaifw';


/***********************************************************************/
// Login via fb
/***********************************************************************/
$url7 = 'http://www.facebook.com/login.php?api_key='.FACEBOOK_API_KEY.'&connect_display=popup&v=1.0&next='.$redirect_url.'&cancel_url=http://www.facebook.com/connect/login_failure.html&fbconnect=true&return_session=true&session_key_only=true&req_perms=read_stream,publish_stream,offline_access';
echo $url7.'<br />';




$graph_url = "https://graph.facebook.com/198371116865752?" . $step_6_token;
$user = json_decode(file_get_contents($graph_url));
var_dump($user);
$access_token = (str_replace('access_token=','',$access_token));

$Facebook = Loader::getFacebook(array(
  'appId' => FACEBOOK_API_KEY,
  'secret' => FACEBOOK_SECRET,
  'cookie' => true,
));

?>