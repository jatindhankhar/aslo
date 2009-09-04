<?php
/* ***** BEGIN LICENSE BLOCK *****
 * Version: MPL 1.1/GPL 2.0/LGPL 2.1
 *
 * The contents of this file are subject to the Mozilla Public License Version
 * 1.1 (the "License"); you may not use this file except in compliance with
 * the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * The Original Code is addons.mozilla.org site.
 *
 * The Initial Developer of the Original Code is
 * The Mozilla Foundation.
 * Portions created by the Initial Developer are Copyright (C) 2009
 * the Initial Developer. All Rights Reserved.
 *
 * Contributor(s):
 *   Jeff Balogh <jbalogh@mozilla.com> (Original Author)
 *
 * Alternatively, the contents of this file may be used under the terms of
 * either the GNU General Public License Version 2 or later (the "GPL"), or
 * the GNU Lesser General Public License Version 2.1 or later (the "LGPL"),
 * in which case the provisions of the GPL or the LGPL are applicable instead
 * of those above. If you wish to allow use of your version of this file only
 * under the terms of either the GPL or the LGPL, and not to allow others to
 * use your version of this file under the terms of the MPL, indicate your
 * decision by deleting the provisions above and replace them with the notice
 * and other provisions required by the GPL or the LGPL. If you do not delete
 * the provisions above, a recipient may use your version of this file under
 * the terms of any one of the MPL, the GPL or the LGPL.
 *
 * ***** END LICENSE BLOCK ***** */



class HubComponent extends Object {

    function startup(&$controller) {
        $this->controller =& $controller;

        $mdc = new HubSite('Mozilla Developer Center', 'http://developer.mozilla.org');
        $wiki = new HubSite('Mozilla Wiki', 'http://wiki.mozilla.org');
        $jetpack = new HubSite('Mozilla Labs - Jetpack', 'http://jetpack.mozillalabs.com');
        $designchallenge = new HubSite('Mozilla Labs Design Challenge', 'http://design-challenge.mozilla.com');

        $this->categories = array(
            new HubCategory('Getting Started', ___('Learn the basics of developing an extension on the Mozilla platform with this section full of beginner\'s guides.'), 'getting-started', array(
                new SubCategory('The Basics', array(
                    new Howto(0, 'Firefox Add-ons Developer Guide',
                              'https://developer.mozilla.org/En/Firefox_addons_developer_guide',
                              $mdc,
                              'In this detailed guide to extension development, you\'ll learn the basics of packaging extensions, building an interface with XUL, implementing advanced processes with XPCOM, and how to put it all together.',
                              'editorspick'),
                    new Howto(0, 'Setting Up an Extension Development Environment',
                              'https://developer.mozilla.org/en/Setting_up_extension_development_environment',
                              $mdc,
                              'This article gives suggestions on how to set up your Mozilla application for extension development, including setting up a profile, changing preferences, and helpful development tools.'),
                    new Howto(0, 'Extension Bootcamp: Zero to Hello World! in 45 Minutes',
                              'http://design-challenge.mozilla.com/resources/#extension_bootcamp',
                              $designchallenge,
                              'In this video tutorial, Myk Melez explains how extensions integrate into Firefox, what they can do, and shows you how to set up an environment to ease their development. He\'ll then walk you through the making of a simple "Hello World!" extension. By the end of this session, you\'ll be an extension developer.',
                              'video'),
                    new Howto(0, 'Building an Extension',
                              'https://developer.mozilla.org/en/Building_an_Extension',
                              $mdc,
                              'This tutorial will take you through the steps required to build a very basic extension - one which adds a status bar panel to the Firefox browser containing the text "Hello, World!"'),
                    new Howto(0, 'Extension Packaging',
                              'https://developer.mozilla.org/en/Extension_Packaging',
                              $mdc,
                              'Learn more details on how extensions are packaged and installed.'),
                    new Howto(0, 'Install Manifests',
                              'https://developer.mozilla.org/en/Install_Manifests',
                              $mdc,
                              'This document explains install manifests (install.rdf) and all of properties available for use in your add-on.')
                )),
                new SubCategory('Other Tutorials', array(
                    new Howto(0, 'How to Develop a Firefox Extension',
                              'http://robertnyman.com/2009/01/24/how-to-develop-a-firefox-extension/',
                              new HubSite('Robert\'s talk', 'http://www.robertnyman.com'),
                              'In this walk-through, Robert Nyman explains how to develop a Firefox extension from scratch.'),
                    new Howto(0, 'How to Build a Firefox Extension',
                              'http://lifehacker.com/264490/how-to-build-a-firefox-extension',
                              new HubSite('Lifehacker', 'http://www.lifehacker.com'),
                              'Lifehacker gives tips and helpful hints on developing your first Firefox extension.'),
                    new Howto(0, 'Firefox Extension Development Tutorial',
                              'http://www.rietta.com/firefox/Tutorial/overview.html',
                              new HubSite('Extend Firefox!', 'http://www.rietta.com/firefox/index.html'),
                              'A multi-page tutorial covering a variety of extension development topics.'),
                    new Howto(0, 'Creating a Status Bar Extension',
                              'https://developer.mozilla.org/en/Creating_a_status_bar_extension',
                              $mdc,
                              'Learn how to create a simple status bar extension and add more advanced functionality in subsequent tutorials, linked below.'),
                    new Howto(0, 'Creating a Dynamic Status Bar Extension',
                              'https://developer.mozilla.org/en/Creating_a_dynamic_status_bar_extension',
                              $mdc,
                              'This article modifies the status bar extension created in the above tutorial by fetching content from a website at a regular interval.'),
                    new Howto(0, 'Adding Preferences to an Extension',
                              'https://developer.mozilla.org/en/Adding_preferences_to_an_extension',
                              $mdc,
                              'This article shows how to add preferences to the dynamic status bar created in the above tutorial.'),
                )),
                new SubCategory('Books', array(
                    new Howto(0, 'Build Your Own Firefox Extension E-book',
                              'http://www.sitepoint.com/books/byofirefoxpdf1/',
                              new HubSite('SitePoint', 'http://www.sitepoint.com'),
                              'With a little JavaScript know-how, author James Edwards will show you just how straightforward it is to build your own Firefox extensions.')
                ))
            )),
            new HubCategory('Extension Development', ___('Once you know the basics, these guides will help you with intermediate to advanced extension development topics.'), 'extension-development', array(
                new SubCategory('Best Practices', array(
                    new Howto(0, 'Respecting the JavaScript Global Namespace',
                              'http://blogger.ziesemer.com/2007/10/respecting-javascript-global-namespace.html',
                              new HubSite('Mark A. Ziesemer', 'http://blogger.ziesemer.com'),
                              'In this blog post, Mark Ziesemer explains how to prevevent global namespace pollution by wrapping your extension\'s variables.'),
                    new Howto(0, '@TODO Brian\'s Security Document',
                              '#',
                              $mdc,
                              '@TODO '),
                    new Howto(0, 'Localizing an Extension',
                              'https://developer.mozilla.org/en/Localizing_an_extension',
                              $mdc,
                              'This article explains how to localize an extension, including XUL and JavaScript strings.')
                )),
                new SubCategory('Security', array(
                    new Howto(0, 'Evaluating Code with Restricted Privileges',
                              'https://developer.mozilla.org/en/Components.utils.evalInSandbox',
                              $mdc,
                              'This article describes the use of Components.utils.evalInSandbox, which is a way to evaluate code (such as remote code) without chrome privileges.'),
                    new Howto(0, 'Creating Sandboxed HTTP Connections',
                              'https://developer.mozilla.org/En/Creating_Sandboxed_HTTP_Connections',
                              $mdc,
                              'This article explains how to created sandboxed HTTP connections which don\'t affect the user\'s cookies.'),
                    new Howto(0, 'Displaying Web Content in an Extension',
                              'https://developer.mozilla.org/En/Displaying_web_content_in_an_extension_without_security_issues',
                              $mdc,
                              'Learn how to display web content in an extension without security issues.'),
                    new Howto(0, 'Five Wrong Reasons to Use eval() in an Extension',
                              'http://adblockplus.org/blog/five-wrong-reasons-to-use-eval-in-an-extension',
                              new HubSite('Adblock Plus Blog', 'http://www.adblockplus.org'),
                              'In this blog post, Wladimir Palant gives five wrong ways to evaluate code in an extension.')
                )),
                new SubCategory('Localization', array(
                    new Howto(0, 'Localizing Extension Descriptions',
                              'https://developer.mozilla.org/en/Localizing_extension_descriptions',
                              $mdc,
                              'Learn how to localize the names and descriptions in extension install manifests.'),
                    new Howto(0, 'Localization and Plurals',
                              'https://developer.mozilla.org/en/Localization_and_Plurals',
                              $mdc,
                              'This article explains how to properly localize strings with plurals.')
                )),
                new SubCategory('Advanced Topics', array(
                    new Howto(0, 'Stupid/Awesome Extension Development Hacks',
                              'http://design-challenge.mozilla.com/resources/#extension_hacks',
                              $designchallenge,
                              'In this video, Jono Xia explains how to go further in extension development using XPCOM, overlays, XHRs, DOM manipulation, etc. in order to make Firefox do things you might have never thought possible.',
                              'video'),
                    new Howto(0, 'Creating Custom Firefox Extensions with the Mozilla Build System',
                              'https://developer.mozilla.org/en/Creating_Custom_Firefox_Extensions_with_the_Mozilla_Build_System',
                              $mdc,
                              'This article describes how to set up the development environment for a large, complex Firefox extension with a need for high-performance, use of third-party libraries in C/C++, or interfaces not exposed via XPCOM.'),
                    new Howto(0, 'Multiple Item Packaging',
                              'https://developer.mozilla.org/en/Multiple_Item_Packaging',
                              $mdc,
                              'This article explains how to create an extension package with multiple items (extensions).'),
                    new Howto(0, 'Extension Documentation Index',
                              'https://developer.mozilla.org/en/Extensions',
                              $mdc,
                              'If you can\'t find what you\'re looking for in the above articles, try the Mozilla Developer Center\'s Extensions landing page.')
                ))
            )),
            new HubCategory('Thunderbird &amp; Mobile Add-ons', ___('Add-ons aren\'t just for Firefox. Learn how to extend other Mozilla applications, such as the Thunderbird mail client and Firefox for mobile devices.'), 'thunderbird-mobile', array(
                new SubCategory('Developing Add-ons for Thunderbird', array(
                    new Howto(0, 'Building a Thunderbird Extension',
                              'https://developer.mozilla.org/en/Building_a_Thunderbird_extension',
                              $mdc,
                              'This tutorial will take you through the steps required to build a very basic extension - one which adds a status bar panel to the Thunderbird Mail Client containing the text "Hello, World!"'),
                    new Howto(0, 'An Overview of Thunderbird Components',
                              'https://developer.mozilla.org/En/Extensions/Thunderbird/An_overview_of_the_Thunderbird_interface',
                              $mdc,
                              'This article describes the Thunderbird user interface, discusses example modifications, and introduces some of the APIs most commonly used by extension developers.'),
                    new Howto(0, 'Thunderbird How-tos',
                              'https://developer.mozilla.org/en/Extensions/Thunderbird/HowTos',
                              $mdc,
                              'This section contains many code samples and how-to articles on common Thunderbird extension tasks.'),
                    new Howto(0, 'Thunderbird Extensions Documentation Index',
                              'https://developer.mozilla.org/en/Extensions/Thunderbird',
                              $mdc,
                              'If you can\'t find what you\'re looking for in the above articles, try the Mozilla Developer Center\'s Thunderbird extensions landing page.'),
                )),
                new SubCategory('Developing Add-ons for Mobile', array(
                    new Howto(0, 'Mobile Architecture',
                              'https://wiki.mozilla.org/Mobile/Fennec/Architecture',
                              $wiki,
                              'This document describes the architecture of Firefox on mobile and includes performance tips for mobile code.'),
                    new Howto(0, 'Mobile Extensions',
                              'https://wiki.mozilla.org/Mobile/Fennec/Extensions',
                              $wiki,
                              'This document explains the specifics on creating an extension for mobile.'),
                    new Howto(0, 'Best Practices for Mobile Extensions',
                              'https://wiki.mozilla.org/Mobile/Fennec/Extensions/BestPractices',
                              $wiki,
                              'Mobile extensions have small screen space and limited resources to work with. This document explains best practices for designing and developing for a mobile environment.'),
                    new Howto(0, 'Mobile Code Snippets',
                              'https://wiki.mozilla.org/Mobile/Fennec/CodeSnippets',
                              $wiki,
                              'Code snippets specific to mobile.')
                ))
            )),
            new HubCategory('Theme Development', ___('Style Mozilla applications the way you want with pixel-perfect themes.'), 'theme-development', array(
                new SubCategory('Getting Started', array(
                    new Howto(0, 'Creating a Skin for Firefox',
                              'https://developer.mozilla.org/en/Creating_a_Skin_for_Firefox',
                              $mdc,
                              'Learn how to find the right files to edit, make your changes, and package up your new theme in this tutorial.'),
                    new Howto(0, 'How to Create a Firefox Theme',
                              'http://www.twistermc.com/blog/2006/09/22/how-to-create-a-firefox-theme/',
                              new HubSite('Blog on a Stick', 'http://www.twistermc.com'),
                              'Thomas McMahon explains how to create a Firefox theme from start-to-finish in this tutorial.')
                )),
                new SubCategory('Theme Development', array(
                    new Howto(0, 'Theme Packaging',
                              'https://developer.mozilla.org/en/Theme_Packaging',
                              $mdc,
                              'This article explains the packaging of themes.'),
                    new Howto(0, 'First Steps in Theme Design',
                              'http://cheeaun.com/blog/2004/12/first-steps-in-theme-design',
                              new HubSite('cheeaun blog', 'http://www.cheeaun.com'),
                              'This blog post by Lim Chee Aun explains the use of -moz-image-region in themes.'),
                    new Howto(0, 'Making Sure Your Theme Works with RTL Locales',
                              'https://developer.mozilla.org/en/Making_Sure_Your_Theme_Works_with_RTL_Locales',
                              $mdc,
                              'It\'s important to make sure your theme works in all locales. This article explains how to tweak your theme to look great for users who browse in right-to-left.'),
                    new Howto(0, 'Theme Documentation Index',
                              'https://developer.mozilla.org/en/Themes',
                              $mdc,
                              'If you haven\'t found what you\'re looking for yet, try the Mozilla Developer Center\'s Themes section.'),
                ))
            )),
            new HubCategory('Other Types of Add-ons', ___('Find information on Search Plug-ins, Jetpack, Personas, and other types of add-ons here.'), 'other-addons', array(
                new SubCategory('Jetpack', array(
                    new Howto(0, 'Jetpack Tutorial',
                              'https://jetpack.mozillalabs.com/tutorial.html',
                              $jetpack,
                              'Learn how easy it is to extend Firefox with Jetpack in this tutorial.'),
                    new Howto(0, 'Jetpack API Documentation',
                              'https://jetpack.mozillalabs.com/api.html',
                              $jetpack,
                              'This Jetpack API documentation is a must-have for working on your Jetpack.')
                )),
                new SubCategory('Personas', array(
                    new Howto(0, 'How to Create Personas',
                              'http://getpersonas.com/demo_create',
                              new HubSite('Personas for Firefox', 'http://www.getpersonas.com'),
                              'Learn how to create your very own Persona with this official walk-through.')
                )),
                new SubCategory('Search Plug-ins', array(
                    new Howto(0, 'Creating OpenSearch Plug-ins for Firefox',
                              'https://developer.mozilla.org/en/Creating_OpenSearch_plugins_for_Firefox',
                              $mdc,
                              'This article explains the structure and requirements of search plug-ins in Firefox.')
                )),
                new SubCategory('Plug-ins', array(
                    new Howto(0, 'Plug-in Documentation Index',
                              'https://developer.mozilla.org/en/Plugins',
                              $mdc,
                              'Plug-ins to Mozilla-based applications are binary components that can display content that the application itself can\'t display natively. Explore the Mozilla Developer Center\'s plug-in documentation index to learn more.')
                )),
            ))
        );

        $this->policies = array(
            new HubCategory('Add-on Submission',
                            ___('Find out what is expected of add-ons we host and our policies on specific add-on practices.'),
                            'submission'),
            new HubCategory('Review Process',
                            ___('What happens after your add-on is submitted? Learn about how our Editors review submissions.'),
                            'reviews'),
            new HubCategory('Maintaining Your Add-on',
                            ___('Add-on updates, transferring ownership, user reviews, and what to expect once your add-on is approved.'),
                            'maintenance'),
            new HubCategory('Recommended Add-ons',
                            ___('How up-and-coming add-ons become recommended and what\'s involved in the process.'),
                            'recommended',
                            array($this->controller->url('/developers/docs/policies/contact'))),
            new HubCategory('Developer Agreement',
                            ___('Terms of Service for submitting your work to our site. Developers are required to accept this agreement before submission.'),
                            'agreement',
                            array($this->controller->url('/pages/developer_faq'))),
            new HubCategory('Contacting Us',
                            ___('How to get in touch with the AMO team regarding these policies or your add-on.'),
                            'contact')
        );
        
        $lorem = 'foo';
        $this->casestudies = array(
            new HubCaseStudy('StumbleUpon',
                             ___('I like turtles!'),
                             'stumbleupon',
                             138,
                             '/img/amo2009/logo-firefox.gif',
                             ___('Learn why people are tripping over StumbleUpon'),
                             new HubSite('StumbleUpon, Inc.', 'http://example.com'),
                             '2001-09-01'),
            new HubCaseStudy('Firebug lorem ipsum',
                             $lorem,
                             'firebug',
                             1843,
                             '/img/amo2009/logo-seamonkey.gif',
                             ___('Firebug action text'),
                             new HubSite('The Firebuggers', 'http://fire.bug'),
                             '2000-01-01'),
            new HubCaseStudy('Adblock Plus dolor sit amet',
                             $lorem,
                             'adblockplus',
                             1865,
                             '/img/amo2009/logo-thunderbird.gif',
                             ___('ABP action text'),
                             new HubSite('Wladimir Palant', 'http://example.com'),
                             '2009-09-01')
            );

        // generate by-slug lookup arrays
        $objecttypes = array('categories', 'policies', 'casestudies');
        foreach ($objecttypes as $type) {
            $slugname = $type.'_slugs';
            $this->$slugname = array();
            foreach ($this->$type as &$item) {
                $this->{$slugname}[$item->slug] = $item;
            }
            unset($item);
        }
    }
}


class HubCategory extends Object {

    function __construct($title, $description, $slug, $items = array()) {
        $this->title = $title;
        $this->description = $description;
        $this->slug = $slug;
        $this->items = $items;
    }
}


class SubCategory extends HubCategory {

    function __construct($title, $items) {
        parent::__construct($title, '', '', $items);
    }
}


class Howto extends Object {

    function __construct($id, $title, $href, $site, $description, $type = 'article') {
        $this->id = $id;
        $this->title = $title;
        $this->href = $href;
        $this->site = $site;
        $this->description = $description;
        $this->type = $type;
    }
}

class HubCaseStudy extends Object {
    function __construct($title, $description, $slug, $addonid, $logo,
        $actiontext, $developer, $firstreleased) {
        $this->title = $title;
        $this->description = $description;
        $this->slug = $slug;
        $this->addonid = $addonid;
        $this->logo = $logo;
        $this->actiontext = $actiontext;
        $this->developer = $developer;
        $this->firstreleased = $firstreleased; // will be fed to strtotime()
    }
}

class HubSite extends Object {

    function __construct($title, $href) {
        $this->title = $title;
        $this->href = $href;
    }
}
