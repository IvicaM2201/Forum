<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';
	
	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>';

	// The ?fin20 part of this link is just here to make sure browsers don't cache it wrongly.
	echo '
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-5963312199467345",
    enable_page_level_ads: true
  });
</script>

	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.css?fin20" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css?fin20" />';
	
	echo '
        <link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index.css?fin20" />';
	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Here comes the JavaScript bits!
	echo '
	<script>
  (function(i,s,o,g,r,a,m){i["GoogleAnalyticsObject"]=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,"script","https://www.google-analytics.com/analytics.js","ga");

  ga("create", "UA-104114400-1", "auto");
  ga("send", "pageview");

</script>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/bootstrap.min.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/jquery-2.2.3.min.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/app.js?fin20"></script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="GeekumNET je forum koji će vam pružati zadovoljstvo u onome čime se bavite. U cilju nam je da skupimo ljude s područja Balkana koji vole dizajn programiranje ili gaminga, ili pak planiraju zavoljeti isto. " />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport" />';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
<div class="wrapper">
   <header class="main-header">
   <div class="logo">
		<a href="', $scripturl, '?action=forum"> </a>
	</div>
   <nav class="navbar navbar-static-top" role="navigation">
    <a href="', $scripturl, '" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="searchbar">Navigacija</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">';
			
					if ($context['user']['is_logged'])
					{
					echo '
						<li class="dropdown messages-menu">';
				
						if ($context['allow_pm'])
						echo '
						<div class="dropdownmessage">
						  <button class="dropbtn">
						  <i style="color:#FFFFFF" class="fa fa-envelope-o"></i> 
						  	 <brojkicapm>', $context['user']['unread_messages'], '</brojkicapm>
							 
							 
							 </button>
						  <div class="dropdownmessage-content">
							 ',pokazipmporuku(),'
							 
							<center><a href="', $scripturl, '?action=pm" style="line-height: 26px; font-size: 13px;">- Prikazi sve -</a></center>
						  </div>
						</div>
						';
			
					echo '
						</li>';
					}
					if ($context['user']['is_logged'])
					{
						echo '
						<a id="novoodgovori" href="', $scripturl, '?action=unread" role="novoodgovori">
							<span>Novo</span>
							
						</a>
						<a id="novoodgovori" href="', $scripturl, '?action=unreadreplies" role="novoodgovori">
							<span>Odgovori</span>
							
						</a>
						<a id="odjavabutton" href="', $scripturl, '?action=logout;' . $context['session_var'] . '=' . $context['session_id']. '" role="odjavabutton">
							<span>Odjava</span>
							
						</a>';
					}
					else
					{
						echo '
						<a id="logbutton" href="', $scripturl, '?action=login" role="logbutton">
							<span>Prijava</span>
							
						</a>
						<a id="regbutton" href="', $scripturl, '?action=register" role="regbutton">
							<span>Registracija</span>
							
						</a>';
						
					}
					echo '
			
        </ul>
      </div>
    </nav>
  </header>';
  echo '
  <aside class="main-sidebar">
  <section class="sidebar">
  <div class="userprofile text-center">
        <div class="userpic">
		<img src="', ($context['user']['avatar'] ? $context['user']['avatar']['href'] : $settings['images_url']. '/theme/noavatar.png'), '" alt="', $txt['profile'], '"class="userpicimg" />
        </div>
        <h3 class="username">', $txt['hello_member_ndt'], ' ', $context['user']['name'], '</h3>
             
      </div>

      <!-- search form (Optional) -->
      <form class="sidebar-form" action="', $scripturl, '?action=search2" method="post" accept-charset="', $context['character_set'], '">
        <div class="input-group">
		<input class="form-control" type="text" name="search" value="', $txt['forum_search'], '" onfocus="this.value = \'\';" onblur="if(this.value==\'\') this.value=\'', $txt['forum_search'], '\';" />
              <span class="input-group-btn">
                <input class="search_button" type="submit" name="submit" value="" />
              </span>
        </div>';
      // Search within current topic?
		if (!empty($context['current_topic']))
		echo '
		<input type="hidden" name="topic" value="', $context['current_topic'], '" />';
						
		// If we're on a certain board, limit it to this board ;).
		elseif (!empty($context['current_board']))
		echo '
	   <input type="hidden" name="brd[', $context['current_board'], ']" value="', $context['current_board'], '" />';
						
		echo '
	</form>
	', template_menu(), '
  </section>
  </aside>
 
  <div class="control-sidebar-bg"></div>';

	// The main content should go here.
	echo '
  <div class="content-wrapper">';
  // Show the navigation tree.
	theme_linktree();
	echo '
    <section class="content-header">
	';

	// Custom banners and shoutboxes should be placed here, before the linktree.

	
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	</section></div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
	 
	<footer class="footerdolje">
		<div class="pull-right hidden-xs">
         <strong>Theme</strong> by <a href="http://geekum.net" target="_blank"><strong>GeekumNET</strong></a>
		 
        </div>
			<strong>', theme_copyright(), '</strong>';

	// Show the load time?
	if ($context['show_load_time'])
		echo '
		<p>', $txt['page_created'], $context['load_time'], $txt['seconds_with'], $context['load_queries'], $txt['queries'], '</p>';

	echo '
	 </footer>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
	
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<ul>
		<li class="navigate_icon"><i class="fa fa-home"></i></li>';

	// Each tree item has a URL and name. Some may have extra_before and extra_after.
	foreach ($context['linktree'] as $link_num => $tree)
	{
		echo '
			<li', ($link_num == count($context['linktree']) - 1) ? ' class="last"' : '', '>';

		// Show something before the link?
		if (isset($tree['extra_before']))
			echo $tree['extra_before'];

		// Show the link, including a URL if it should have one.
		echo $settings['linktree_link'] && isset($tree['url']) ? '
				<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

		// Show something after the link...?
		if (isset($tree['extra_after']))
			echo $tree['extra_after'];

		// Don't show a separator for the last one.
		if ($link_num != count($context['linktree']) - 1)
			echo ' &#187;';

		echo '
			</li>';
	}
	echo '
		</ul>
	</div>';

	$shown_linktree = true;
}
// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
			<ul class="menulijevo" >';

	foreach ($context['menu_buttons'] as $act => $button)
	{
		echo '
				<li id="button_', $act, '">
					<a class="', $button['active_button'] ? 'active ' : '', 'firstlevel" href="', $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '>
						<span class="responsive_menu ', $act, '"></span>

						<span class="', isset($button['is_last']) ? 'last ' : '', 'firstlevel">', $button['title'], '</span>
					</a>';
		
		echo '
				</li>';
	}

	echo '
			</ul>';
}
// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><i class="fa fa-'.$key.' fa-fw"></i><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	echo '
		<div class="buttonlist', !empty($direction) ? ' float' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<ul class="nav nav-pills">',
				implode('', $buttons), '
			</ul>
		</div>';
}
function pokazipmporuku()
{
	global $scripturl, $db_prefix, $context, $user_info, $smcFunc;
	
	//Gimme the newest pm, if we have.
	if(!empty($user_info['unread_messages'])) {
		$request = $smcFunc['db_query']('', '
			SELECT
				pm.id_pm, pm.id_member_from, pm.from_name, pm.msgtime, pm.subject, pm.body, m.avatar, m.real_name
			FROM {db_prefix}personal_messages AS pm
				LEFT JOIN {db_prefix}pm_recipients AS pmr ON (pmr.id_pm = pm.id_pm)
				LEFT JOIN {db_prefix}members AS m ON (pm.id_member_from = m.id_member)
			WHERE pmr.is_new = 1 AND pmr.id_member = {int:current_member}
			ORDER BY pm.msgtime DESC',
			array(
				'current_member' => $user_info['id'],
			)
		);
		$context['pm_informer'] = array();

		while ($row = $smcFunc['db_fetch_assoc']($request))
		{
			//You can't be so looongggg. He he. :)
			if ($smcFunc['strlen']($row['body']) > 200)
				$row['body'] = $smcFunc['substr']($row['body'], 0, 200) . '<a href="' . $scripturl . '?action=pm#msg' . $row['id_pm'] . '">...[Procitaj Sve]</a>';

			// Build the array.
			$context['pm_informer'] = array(
			    'avatar' => $row['avatar'],
				'from' => $row['id_member_from'],
				'sender' => $row['real_name'],
				'msgtime' => timeformat($row['msgtime'], false),
				'subject' => $row['subject'],
				'body' => parse_bbc($row['body']),
			);
			if(!empty($context['user']['unread_messages']) && !empty($context['pm_informer'])) 
			{
			echo '
		    <div class="porukanovabox">
		    	<div class="porukanovaavatar">
					', PrikaziAvatarIndex($context['pm_informer']['from']) ,'
		        	&nbsp;
				</div>
				<div class="porukanovasalje">
					<a href="' . $scripturl . '?action=profile;u=', $context['pm_informer']['from'], '"> ', $context['pm_informer']['sender'], ' </a> 
				</div>
					<div class="porukanaslov"><a href="' . $scripturl . '?action=pm#msg' . $row['id_pm'] . '">', $context['pm_informer']['subject'], '</a></div> <br>
					<div class="porukadatum">', $context['pm_informer']['msgtime'], '</div> 
				<br>
		    </div>';
			}

		}
		$smcFunc['db_free_result']($request);
	}
	else
	{
		echo'<center><p style="font-size: 13px;">Trenutno nema novih poruka.</p></center>';
	}
	//Do we have some new pms? Show the newest.
	
}
//Napredni system avatara by Ino
function PrikaziAvatarIndex($id)
{
	global $db_prefix;
    //Provjerava dali korisnik postoji
	$member_postoji = mysql_query("SELECT * FROM `" . $db_prefix . "members` WHERE `id_member`='$id'");

    if(mysql_num_rows($member_postoji) > 0)
	{
	   //ako postoji
       
        
        //citanje avatara koji je dodan putem uploada na profil
        $memberavatar = mysql_query("SELECT * FROM `" . $db_prefix . "attachments` WHERE `id_member`='$id' AND `attachment_type`='0'");

        //ispisivanje
        if(mysql_num_rows($memberavatar) > 0)
        {
            while($row = mysql_fetch_array($memberavatar))
            {
                $avatar_upload	= $row['id_attach'];			//ID Slike
            }
        }
        else
        {
            $avatar_upload = '';
        }


        //citanje ukoliko je avatar dostavljen putem linka
        $member = mysql_query("SELECT * FROM `" . $db_prefix . "members` WHERE `id_member`='$id'");

        //ispisivanje
        while($row = mysql_fetch_array($member))
        {
            $member_avatar	= $row['avatar'];				//Link avatara
        }

        //ako je avatar dostavljen putem uploada
        if(!empty($avatar_upload))
        {
            $kon_avatar = '<img src="?action=dlattach;attach=' . $avatar_upload . ';type=avatar">';
        }
        else//ako je avatar dostavljen putem linka
        {
            if (strpos($member_avatar,'http://') !== false) {
                $kon_avatar = '<img src="' . $member_avatar . '">';
            } else { //ako je avatar odabran putem SMF avatara
                $kon_avatar = '<img src="avatars/' . $member_avatar . '">';
            }	
            if(empty($member_avatar))//ako avatar nije dostavljen linkom, postavlja se provjera dali je dodan uploadom
            {
                $kon_avatar = '<img src="http://i.imgur.com/KVEwCUy.png">';
            }
        }
    }
    else//ako ne postoji
    {
        $kon_avatar = '<img src="http://i.imgur.com/KVEwCUy.png">';
    }
	return $kon_avatar;
}
function pokazitag()
{
	global $scripturl, $db_prefix, $user_settings, $context, $user_info, $smcFunc;

	if (!empty($user_settings['unread_mentions'])){
    	$id = $user_info['id'];
    	$rezultati = mysql_query("SELECT * FROM `" . $db_prefix . "log_mentions` WHERE `id_mentioned`='$id' ORDER BY `time` DESC");
	        while($row = mysql_fetch_array($rezultati)){
	            $id_post = $row['id_post'];
	            $id_member = $row['id_member'];
	            $id_mentioned = $row['id_mentioned'];
	            $time = $row['time'];
	            $unseen = $row['unseen'];

			   	$post = mysql_query("SELECT * FROM `" . $db_prefix . "messages` WHERE `id_msg`='$id_post'");
			        while($row = mysql_fetch_array($post)){
			            $post_name = $row['subject'];
			        }
			    echo '
			    <div class="porukanovabox">
					' . PrikaziAvatarIndex($id_member) . '
			        <b>Poruka:</b>&nbsp;<a href="?msg=' . $id_post . '">' . $post_name . '</a><br />
			        <b>Tagovao:</b>&nbsp;<a href="?action=profile;u=' . $id_member . '">' . PrikaziImeIndex($id_member) . '</a><br />
			        <b>Datum:</b>&nbsp;' . timeformat($time) . '
			    </div>';
	        }
    }
    else
	{
		echo'<center><p style="font-size: 13px;">Trenutno nema novih tagova.</p></center>';
	}
}

//Funkcija za prikazivanje korisnickog imena
function PrikaziImeIndex($id)
{
	global $db_prefix;
	$member = mysql_query("SELECT * FROM `" . $db_prefix . "members` WHERE `id_member`='$id'");

	//ispisivanje
	while($row = mysql_fetch_array($member))
	{
		$member_name	= $row['member_name'];				//Ime korisnika
	}
	return $member_name;
}



?>