<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminPortfolioController extends ModuleAdminController
{
    public function __construct()
    {
        $this->className = 'Portfolios';
        $this->table = 'portfolio';
        $this->deleted = false;
        $this->identifier = 'id_portfolio';
        $this->lang = true;
        $this->bootstrap = true;
        $this->context = Context::getContext();

        parent::__construct();

        $this->fields_list = [
            'id_portfolio' => ['title' => $this->trans('ID'), 'align' => 'center', 'class' => 'fixed-width-xs', 'search' => false],
            'heading' => ['title' => $this->trans('Name'), 'align' => 'center', 'search' => false],
            'active' => ['title' => $this->trans('Active'), 'align' => 'center', 'active' => 'status', 'class' => 'fixed-width-sm', 'type' => 'bool', 'search' => false],
        ];
    }
    public function renderForm()
    {
        $obj = $this->loadObject(true);

        $image_url = '';

        if ($obj->id_portfolio) {
            $imgPathUrl = $obj->getImgPath($obj->id_portfolio);
            $image = _PS_IMG_DIR_ . 'portfolio/' . (int) $obj->id_portfolio . '/' . basename((string) $imgPathUrl);

            if (!empty($imgPathUrl)) {
                $image_url = ImageManager::thumbnail(
                    $image,
                    $this->table . '_' . (int) $obj->id_portfolio . '.' . $this->imageType,
                    250,
                    $this->imageType,
                    true,
                    true
                );
            }
        }
        $activeArray = [
            'type' => 'switch',
            'label' => $this->trans('Active'),
            'name' => 'active',
            'required' => false,
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'active_on',
                    'value' => 1,
                    'label' => $this->trans('Enabled'),
                ],
                [
                    'id' => 'active_off',
                    'value' => 0,
                    'label' => $this->trans('Disabled'),
                ],
            ],
        ];
        $this->fields_form = [
            'tinymce' => true,
            'legend' => [
                'title' => $this->trans('Add/Edit Portfolio'),
                'icon' => 'icon-cogs',
            ],
            'input' => [
                $activeArray,
                [
                    'type' => 'text',
                    'label' => $this->trans('Heading'),
                    'name' => 'heading',
                    'lang' => true,
                    'required' => true,
                    'maxlength' => 255,
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Sub Heading'),
                    'name' => 'subheading',
                    'lang' => true,
                    'maxlength' => 255,
                ],
                [
                    'type' => 'text',
                    'label' => $this->trans('Link'),
                    'name' => 'link',
                    'lang' => false,
                    'hint' => $this->trans('Enter a complete HTTP or HTTPS URL, or leave empty.'),
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->trans('Description'),
                    'name' => 'description',
                    'lang' => true,
                    'autoload_rte' => true,
                    'cols' => 93,
                    'rows' => 30,
                    'required' => false,
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->trans('Features'),
                    'name' => 'features',
                    'lang' => true,
                    'cols' => 93,
                    'autoload_rte' => true,
                    'rows' => 30,
                    'required' => false,
                ],
                [
                    'type' => 'file',
                    'label' => $this->trans('Image'),
                    'name' => 'img',
                    'display_image' => true,
                    'image' => $image_url ? $image_url : false,
                    'hint' => $this->trans('Upload Porfolio Image from your computer.'),
                ],
            ],
            'submit' => [
                'title' => $this->trans('Save'),
            ],
        ];
        if ($obj->id_portfolio) {
            $image = $image_url;
            $this->context->smarty->assign('image', $image);
        }
        return parent::renderForm();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('statusportfolio')) {
            parent::postProcess();
        }

        if (Tools::isSubmit('deleteportfolio')) {
            parent::postProcess();
        }

        if (Tools::isSubmit('submitBulkenableSelectionportfolio')) {
            $selected_items = Tools::getValue('portfolioBox');
            $action = 1;
            if ($selected_items) {
                foreach ($selected_items as $item_id) {
                    Portfolios::changeStatus($item_id, $action);
                }
            }
        }

        if (Tools::isSubmit('submitBulkdisableSelectionportfolio')) {
            $selected_items = Tools::getValue('portfolioBox');
            $action = 0;
            if ($selected_items) {
                foreach ($selected_items as $item_id) {
                    Portfolios::changeStatus($item_id, $action);
                }
            }
        }

        if (Tools::isSubmit('submitAddportfolio')) {
            $languages = Language::getLanguages(false);
            $headings = [];
            $sourceHeading = '';
            $englishLanguageId = 0;
            foreach ($languages as $language) {
                if (Tools::strtolower((string) $language['iso_code']) === 'en') {
                    $englishLanguageId = (int) $language['id_lang'];
                    break;
                }
            }

            foreach ($languages as $language) {
                $idLang = (int) $language['id_lang'];
                $headings[$idLang] = trim((string) Tools::getValue('heading_' . $idLang, ''));
                if ($headings[$idLang] !== '' && ($sourceHeading === '' || $idLang === $englishLanguageId)) {
                    $sourceHeading = $headings[$idLang];
                }
            }

            if ($sourceHeading === '') {
                $this->errors[] = $this->trans('Enter a heading in at least one language.');
            }

            $existingHeadings = [];
            $idPortfolio = (int) Tools::getValue('id_portfolio');
            if ($idPortfolio > 0) {
                $existing = new Portfolios($idPortfolio);
                if (is_array($existing->heading)) {
                    $existingHeadings = $existing->heading;
                }
            }

            foreach ($languages as $language) {
                $idLang = (int) $language['id_lang'];
                if ($headings[$idLang] === '') {
                    // Preserve existing translations when editing; otherwise use the entered title.
                    $headings[$idLang] = isset($existingHeadings[$idLang]) && trim((string) $existingHeadings[$idLang]) !== ''
                        ? trim((string) $existingHeadings[$idLang])
                        : $sourceHeading;
                }
                if ($headings[$idLang] !== '' && Tools::strlen($headings[$idLang]) > 255) {
                    $this->errors[] = sprintf($this->trans('The heading must be 255 characters or fewer in %s.'), $language['name']);
                }
                $_POST['heading_' . $idLang] = $headings[$idLang];

                $subheading = trim((string) Tools::getValue('subheading_' . $idLang, ''));
                if (Tools::strlen($subheading) > 255) {
                    $this->errors[] = sprintf($this->trans('The subheading must be 255 characters or fewer in %s.'), $language['name']);
                }
                foreach (['description', 'features'] as $htmlField) {
                    $_POST[$htmlField . '_' . $idLang] = Tools::purifyHTML((string) Tools::getValue($htmlField . '_' . $idLang, ''));
                }
            }

            $link = trim((string) Tools::getValue('link', ''));
            if ($link !== '') {
                $parts = parse_url($link);
                if (Tools::strlen($link) > 255 || !Validate::isAbsoluteUrl($link) || empty($parts['scheme']) || !in_array(Tools::strtolower($parts['scheme']), ['http', 'https'], true)) {
                    $this->errors[] = $this->trans('Enter a valid HTTP or HTTPS link, or leave the field empty.');
                }
            }

            $file = Tools::fileAttachment('img');
            if ($file && !empty($file['name'])) {
                $file['type'] = $file['mime'];
                $isUploadedFile = !empty($file['tmp_name']) && is_uploaded_file($file['tmp_name']);
                $imageInfo = $isUploadedFile ? @getimagesize($file['tmp_name']) : false;
                if (ImageManager::validateUpload($file, Tools::convertBytes(ini_get('upload_max_filesize'))) || !$imageInfo || !in_array($imageInfo['mime'], ['image/jpeg', 'image/png', 'image/gif'], true)) {
                    $this->errors[] = $this->trans('The image is too large or its format is not supported.');
                } else {
                    $file['detected_mime'] = $imageInfo['mime'];
                }
            }

            if (!empty($this->errors)) {
                return;
            }

            parent::postProcess();

            if ($file && !empty($file['name']) && empty($this->errors)) {
                $idPortfolio = (int) Tools::getValue('id_portfolio');
                if ($idPortfolio < 1 && isset($this->object->id)) {
                    $idPortfolio = (int) $this->object->id;
                }
                $extensions = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/gif' => 'gif',
                ];
                $mime = isset($file['detected_mime']) ? Tools::strtolower($file['detected_mime']) : '';
                if ($idPortfolio < 1 || !isset($extensions[$mime])) {
                    $this->errors[] = $this->trans('The portfolio entry was saved, but the image could not be processed.');
                    return;
                }

                $directory = _PS_IMG_DIR_ . 'portfolio/' . $idPortfolio;
                if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                    $this->errors[] = $this->trans('The image directory could not be created.');
                    return;
                }
                $filename = 'image-' . $idPortfolio . '-' . bin2hex(random_bytes(8)) . '.' . $extensions[$mime];
                $absolutePath = $directory . DIRECTORY_SEPARATOR . $filename;
                if (!move_uploaded_file($file['tmp_name'], $absolutePath)) {
                    $this->errors[] = $this->trans('The image could not be uploaded.');
                    return;
                }
                $imgPath = 'portfolio/' . $idPortfolio . '/' . $filename;
                if (!Db::getInstance()->update('portfolio', ['img' => pSQL($imgPath)], 'id_portfolio = ' . $idPortfolio)) {
                    @unlink($absolutePath);
                    $this->errors[] = $this->trans('The image was uploaded, but its path could not be saved.');
                }
            }
        }
    }

    public function renderList()
    {
        $this->addRowAction('add');
        $this->addRowAction('edit');
        $this->addRowAction('delete');
        return parent::renderList();
    }
}
