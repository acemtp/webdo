<?php


function display_priority($prio)
{
	$prio_text = array('Doit avoir', 'Adorerais avoir', 'Aimerais avoir', 'J\'y pense', 'Suggestion');

	for($i=0; $i<6-intval($prio); $i++) echo '<img src="images/star.png" title="'.$prio_text[intval($prio)-1].'" alt="'.$prio_text[intval($prio)-1].'"/>';
}

function display_gifts($title, $gifts, $display, $order='', $way='', $url='')
{
	echo "<h2>$title</h2>";

	if (count($gifts) == 0) {
		echo '<p>Il n\'y a aucun cadeau... Comme c\'est triste <img src="images/snif.gif"/></p>';
		return;
	}

	$row_name = array(
		'creeLe' => 'Créé le',
		'titre' => 'Titre',
		'pour' => 'Pour',
		'priorite' => 'Priorité',
		'reservePar' => 'Réservé par',
		'achetePar' => 'Acheté par',
	);

	echo '<table class="table table-striped">';
	
	// display table header
	echo '<tr>';
	foreach($display as $row)
		if(isset($row_name[$row]))
		{
			echo '<th>';
			if($url != '') {
				echo '<a href="'.$url;
				echo '&order='.$row;
				if($row == $order && $way == 'asc') echo '&way=desc';
				else echo '&way=asc';
				echo '">';
				if($row == $order) echo '<img src="images/sort_'.$way.'.gif" />';
			}
			echo $row_name[$row];
			if($url != '') echo '</a>';
			echo '</th>';
		}
	echo '</tr>';

	$sugg = false;
	
	foreach ($gifts as $gift)
	{
		$gid = $gift['id'];

		echo '<tr>';
		foreach($display as $row)
		{
			if(isset($row_name[$row]))
			{
				$value = $gift[$row];
				echo '<td>';
				if($row == 'titre')
				{
					if (isset($gift['creePar']) && isset($gift['pour']) && $gift['creePar'] != $gift['pour']) { echo "* "; $sugg = true; }

					$value = preg_replace('/\[(.+)\](.+)/i', '<span class="label">$1</span> $2', $value);

					echo '<a onclick="location.href=\'kdo.php?id='.$gid.'\'" rel="popover" data-content="<img src=\''.$gift['image'].'\'/>">'.$value.'</a>';
				}
				else if($row == 'reservePar' && $value == '')
				{
					echo '<a href="r_reserve.php?idKdo='.$gid.'" rel="tooltip" title="Je réserve"><img src="images/icon_reserve.png"/></a>';
				}
				else if($row == 'achetePar' && $value == '')
				{
					echo '<a href="r_achete.php?idKdo='.$gid.'" rel="tooltip" title="J\'ai acheté"><img src="images/icon_buy.png"</a>';
				}
				else if($row == 'achetePar' || $row == 'reservePar' || $row == 'pour')
				{
					echo '<span class="label">';
					echo prenom($value);
					echo '</span>';
					if($row == 'reservePar' && isset($gift['reserveLe'])) echo ' <img src="images/icon_calendar.gif" rel="tooltip" title="Le '.$gift['reserveLe'].'" alt="" />';
					if($row == 'achetePar' && isset($gift['acheteLe'])) echo ' <img src="images/icon_calendar.gif" rel="tooltip"  title="Le '.$gift['acheteLe'].'" alt="" />';
				}
				else if($row == 'priorite')
				{
					//echo display_priority($value);
					aff_priorite(false, $value);
				}
				else
				{
					echo $value;
				}
				echo '</td>';
			}
		}

		if(in_array('archive', $display))
		{
			echo '<td><a href="r_archive_kdo.php?id='.$gid.'" rel="tooltip" title="Archiver ce cadeau">';
			echo '<img src="images/icon_trash.gif" alt="Archiver"/>';
			echo '</a></td>';
		}
		if(in_array('supprime', $display))
		{
			echo '<td><a href="javascript:alertSup('.$gid.');" rel="tooltip" title="Supprimer ce cadeau">';
			echo '<img src="images/icon_trash.gif" alt="Supprimer"/>';
			echo '</a></td>';
		}
		echo '</tr>';
	}
	echo '</table>';

	if($sugg) echo "<p>* ce cadeau a été suggéré.</p>";
}

?>