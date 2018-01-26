<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Table Media
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table
 * @since       __DEPLOY_VERSION__
 */
class RedshopTableMedia extends RedshopTable
{
	/**
	 * The table name without the prefix. Ex: cursos_courses
	 *
	 * @var  string
	 */
	protected $_tableName = 'redshop_media';

	/**
	 * The table key column. Usually: id
	 *
	 * @var  string
	 */
	protected $_tableKey = 'media_id';

	/**
	 * @var string
	 */
	public $media_name = '';

	/**
	 * @var string
	 */
	public $media_alternate_text = '';

	/**
	 * @var string
	 */
	public $media_section = '';

	/**
	 * @var integer
	 */
	public $section_id = '';

	/**
	 * @var string
	 */
	public $media_type = '';

	/**
	 * @var string
	 */
	public $media_mimetype = '';

	/**
	 * @var integer
	 */
	public $published = '';

	/**
	 * @var integer
	 */
	public $ordering = '';

	/**
	 * @var string
	 */
	public $scope = '';
}
