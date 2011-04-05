<?php
/*
Copyright (c) 2011, Christoph Herbst.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class smwaccess
{
   const smwcookie = "./.smwcookie";

   static function login($url,$user,$pass){
	@unlink(self::smwcookie);
	$content=self::get_content($url);
	preg_match('/<input.*wpLoginToken.*value="([a-f0-9]+)"/',$content,$matches);
	$token = $matches[1];
	preg_match('/<form.*userlogin.*action="(.+)"/',$content,$matches);
	$login_url = parse_url($url);
	$action = $login_url['scheme'].'://'.$login_url['host'].$matches[1];
	$user=urlencode($user);

	$postdata="wpName=$user&wpPassword=$pass&wpRemember=1&wpLoginToken=$token";
	$ch = curl_init();
	$action = 'http://87.238.194.42/df4/index.php?title=Spezial:Anmelden&action=submitlogin&type=login';
	curl_setopt ($ch, CURLOPT_URL,$action);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_USERAGENT, 
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, self::smwcookie);
	curl_setopt ($ch, CURLOPT_COOKIEFILE, self::smwcookie);
	curl_setopt ($ch, CURLOPT_REFERER, $url);

	curl_setopt ($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt ($ch, CURLOPT_POST, 1);
	$result = curl_exec ($ch);
	curl_close($ch);
	return $result;
   }

   static function get_content($url){
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL,$url);
	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt ($ch, CURLOPT_USERAGENT, 
		"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_COOKIEJAR, self::smwcookie);
	curl_setopt ($ch, CURLOPT_COOKIEFILE, self::smwcookie);
	$result = curl_exec ($ch);
	curl_close($ch);
	return $result;
   }
}
?>
