<?php

namespace LeadingSystems\Api;

use Contao\Backend;
use Contao\DataContainer;
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_ls_api_user'] = array
(
    'config' => array
    (
        'dataContainer'               => DC_Table::class,
        'enableVersioning'            => true,
        'onload_callback'             => array
        (
            array('LeadingSystems\Api\tl_ls_api_user', 'checkPermission')
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id'                  => 'primary',
                'username'            => 'unique'
            )
        )
    ),
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => DataContainer::MODE_SORTED,
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'fields'                  => array('username'),
            'disableGrouping'         => false,
            'panelLayout'             => 'sort,search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('description', 'username'),
            'format'                  => '<strong title="%s">%s</strong>'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_ls_api_user']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.svg'
            ),
            'copy' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_ls_api_user']['copy'],
                'href'                => 'act=copy',
                'icon'                => 'copy.svg'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_ls_api_user']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.svg',
                'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_ls_api_user']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.svg'
            )
        )
    ),
    'palettes' => array
    (
        'default'                     => 'username,password;description;'
    ),
    'fields' => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'username' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ls_api_user']['username'],
            'exclude'                 => true,
            'search'                  => true,
            'sorting'                 => true,
            'flag'                    => DataContainer::SORT_INITIAL_LETTER_ASC,
            'inputType'               => 'text',
            'eval'                    => array('mandatory' => true, 'rgxp' => 'extnd', 'nospace' => true, 'unique' => true, 'maxlength' => 64, 'tl_class'=>'w50'),
            'sql'                     => "varchar(64) BINARY NULL"
        ),
        'password' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ls_api_user']['password'],
            'exclude'                 => true,
            'inputType'               => 'password',
            'eval'                    => array('mandatory' => true, 'preserveTags' => true, 'minlength' => 10, 'tl_class' => 'clr'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_ls_api_user']['description'],
            'exclude'                 => true,
            'inputType'               => 'textarea',
            'sql'                     => "text NULL"
        )
    )
);

class tl_ls_api_user extends Backend {
    public function __construct() {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

    /**
     * Check permissions to edit table tl_ls_api_user
     *
     * @throws \Contao\CoreBundle\Exception\AccessDeniedException
     */
    public function checkPermission()
    {
        if (!$this->User->isAdmin)
        {
            throw new \Contao\CoreBundle\Exception\AccessDeniedException('Access restricted to administrators.');
        }
    }
}