================================================================
Green Marinée 1.0 - Designed by Ian Main | http://e-lusion.com
[Theme built for WordPress 1.5 - Alex King - The WordPress 1.5 Themes Competition 2005] 
[Licensed under GPL]
[March 2005]

How to install
-----------------

You should be looking at the Green Marinée folder right now if your reading this; if your not
unzip the archive. You should than see a folder called greenmarinee. 

Open up your FTP program and login to your server. Navigate to your WordPress folder and upload the entire folder (greenmarinee) to your wp-content/themes folder.

Login to WordPress administration normally and select Presentation from the menu.

You should see a freshly installed theme called Green Marinée 1.0 and a few basic details about the theme. 

Active this theme by clicking Select on the right.

View your site to see Green Marinée in all her glory. 
	Note: A page refresh may be required.


Making it yours
-----------------

+ Author section
+ Tagline description


----Author section----
I have added a few little features to help you customise Green Marinée.

In the file sidebar.php I have included a section for you to briefly add a description about yourself or the site. 

To active this you will need to open sidebar.php (located in greenmarinee) with Notepad/Textedit or a html editor and locate the author section. (commented by default)

Remove the comment by deleting the <!-- at the beginning and the --> at the end. Also delete the line "Here is a section you can use to briefly talk about yourself or your site. Uncomment and delete this line to use."

Withing the paragraph tags <p> write a brief sentence about yourself. If you prefer to use this section to talk about the site change "The Author" to what ever you like.

Save your changes and upload and replace the file located on the server.

----Tagline description----
You may have noticed you site's tagline has disappeared. This is turned off by default; but don't worry here is how you can get it back.

Firstly make sure you actually do have a tagline. To check login to your WordPress admin panel and select Options from the menu. You will see either an empty box or your site's tagline next to the Tagline: section. Save the changes you made if any and logout.

On your local machine open up the file style.css (located in greenmarinee) with Notepad/Textedit or a html editor and locate both the h1 and .tagline sectors.

To use the tagline uncomment the second h1 by removing <!-- --> and the comment description. Please make sure you comment the first h1 by adding <!-- to the beginning and --> to the end.

Now scroll down to the structure area and locate .tagline. Again uncomment and remove the comment description to active.

Open up header.php (located in greenmarinee) with Notepad/Textedit or a html editor and locate <!-- Tag line description is off by default. Please see readme.txt or CSS(h1,tagline) for more info <div class="tagline"><?php // remove bloginfo('description'); ?></div> -->

Again remove the comment and description and the "// remove" from the php call. 

Save your changes and upload and replace the files located on the server.

Extra
-----------------
I will be creating a few colour variations in the near future to accommodate the community. Please head over to my site http://e-lusion.com for more information.

+Browser support
[I have tested Green Marinée in the following browsers]

Firefox
Safari
Internet Explorer 6/5.5/5
Internet Explorer 5.2(Mac)
Lynx

+Credit
If you enjoy this theme please let me know; it only takes 5 minutes of your life. You can find details at: http://e-lusion.com/contact.htm

+Last words
Thanks to the WordPress team for releasing a tidy package
Thanks to all who use Human Condition and email me ever bloody day asking when she will be released to WordPress 1.5!
Thanks to Alex King(http://alexking.org) for another great competition. Let's see if I can bet 3rd place this year!

I hope you enjoy Green Marinée - Ian Main
================================================================