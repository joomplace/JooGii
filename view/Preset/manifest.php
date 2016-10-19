<?xml version="1.0" encoding="utf-8"?>
<extension type="component" version="3.x" method="upgrade">
    <name>com_<?= lcfirst($component); ?></name>
    <author><?= JFactory::getUser()->name; ?></author>
    <creationDate><?= JHtml::_('date','now','F Y'); ?></creationDate>
    <license>GNU General Public License version 2 or later; see	LICENSE</license>
    <authorEmail><?= JFactory::getUser()->email; ?></authorEmail>
    <version>0.1.0</version>
    <description>COM_<?= strtoupper($component) ?>_XML_DESCRIPTION</description>
    <?php if($files || $folders){ ?>
    <files folder="site">
        <?php foreach ($files as $file){?>
            <filename><?= $file ?></filename>
            <?php
        } ?>
        <?php foreach ($folders as $folder){?>
            <folder><?= $folder ?></folder>
            <?php
        } ?>
    </files>
    <?php } ?>
    <administration>
        <menu>com_<?= strtolower($component); ?></menu>
        <?php if($admin_files || $admin_folders){ ?>
        <files folder="admin">
            <?php foreach ($admin_files as $afile){?>
                <filename><?= $afile ?></filename><?php
            } ?>
            <?php foreach ($admin_folders as $afolder){?>
                <folder><?= $afolder ?></folder><?php
            } ?>
        </files>
        <?php } ?>
    </administration>
</extension>