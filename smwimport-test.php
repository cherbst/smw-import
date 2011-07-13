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

class smwimport_test
{
  static function get_events(){
	for ( $i=0;$i<100;$i++){
		$event = array(
		'type'  => 'Veranstaltung',
		'label' => 'SMW Event'.$i,
		'title' => 'SMW Event'.$i,
		'eventtype'  => 'concert',
		'date_begin' => '2011-'.(($i%12)+1).'-'.(($i%29)+1).' 10:00',
		'date_end' => ($i%10==0)?('2011-'.(($i%12)+1).'-'.((($i+1)%29)+1).' 10:00'):'',
		'short_description' => 'SMW imported event '.$i,
		'long_description' => '<strong>Newer imported event content</strong>',
		'genre' => 'rock',
		'homepage' => array( 'www.test1.de','www.test2.de','www.test3.de'),
		'location' => 'Werkstatt',
		'house' => 'big house',
		'room' => '203',
		'age' => '18'
		);

		$data[] = $event;
	}
	$data[] = array(
		'type'  => 'Veranstaltung',
		'label' => 'ULTRASH',
		'title' => 'ULTRASH - FESTIVAL V',
		'eventtype'  => 'Festival',
		'date_begin' => '2011-05-20 10:00',
		'date_end' => '2011-05-21 10:00',
		'long_description' => '
Am 20./21.5.2011 wird das mittlerweile fünfte Ultrash-Festival in Zusam-
menarbeit von RASH Berlin-Brandenburg , Scortesi , dem Filmstadtinferno
99 , Stara Garda und weiteren Unterstützer_innen in Potsdam in der neu-
en Location „Freiland“ stattfinden. Folgende Bands werden aufteten:
The Offenders (Ska/Italien/Berlin)

United Struggle (AFA-Oi!/Düsseldorf)
Redska (Ska/Italien)
Jesus Skins (Christlicher-AFA-Oi!)
Bier Iki Ütsch (Punk/Potsdam)
The Last Minute (Ska/Ungarn)
Produzenten der Froide (AFA-Oi!/Stuttgart)
The Bayonets (AFA – Stretpunk/Serbien)
Lea-Won (Hip Hop/München)
und Asi & Jaycop (Hip Hop/Potsdam)',
		'genre' => 'rock',
		'homepage' => array( 'http://ultrash.blogsport.de/vorankuendigung-2011/'),
		'location' => 'Freiland',
		'house' => 'big house',
		'room' => '203',
		'age' => '18',
		'image_big' => array('url' => 'http://87.238.194.42/cmsbilder/ultrash_v.jpg',
				     'title' => 'Ultrash V'),
		'sponsor' => array(
			  array('url' => 'http://87.238.194.42/cmsbilder/antifalogo.gif',
			        'title' => 'Antifa'),
			  array('url' => 'http://87.238.194.42/cmsbilder/scortel_logo.jpg',
			        'title' => 'Scortel'),
			  array('url' => 'http://87.238.194.42/cmsbilder/asta_07.png',
			        'title' => 'Asta')
			)
		);

	return $data;
  }

  static function get_galleries(){
	$gallery_folder =  dirname(__FILE__) . '/../../themes/freiland/gallery';
	if (!($dh = opendir($gallery_folder)))
		return new WP_Error('smwimport-test',__('Could not get test galleries from:').$gallery_folder);

	while (($dir = readdir($dh)) !== false) {
		if ( $dir == '.' || $dir == '..' ) continue;
		$folder = $gallery_folder .'/'. $dir;
		if ( !is_dir($folder) )
			continue;
		$data[] = array(
		'type'  => 'Gallery',
		'name' => $dir,
		'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aene-
an commodo ligula eget dolor. Aenean massa. Cum sociis natoque
penatibus et magnis dis parturient montes, nascetur ridiculus mus.
Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.
Nulla consequat massa quis enim. Donec pede justo, fringilla vel,
aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imper-
diet a, venenatis vitae, justo.
'.$i,
		'gallery_folder' => $folder);
	}
	return $data;
  }

  public static function get_sources(){
	$sources = array(
		array(smwimport_test,get_events),
		array(smwimport_test,get_galleries)
	);
	return $sources;
  }

}

?>
