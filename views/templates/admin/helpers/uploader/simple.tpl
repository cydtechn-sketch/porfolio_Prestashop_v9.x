{*
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
*}

{if empty($image)}{assign var="image" value=''}{/if}
<div class="panel col-lg-12">
    <h3>{l s='Add Picture' mod='portfolio'}</h3>
    <p class="alert alert-info">{l s='The file must be image.' mod='portfolio'}</p>
    <div class="form-group">
        <label for="file" class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" title="{l s='Upload image from your computer.' mod='portfolio'}">
                {l s='image file' mod='portfolio'}
            </span>
        </label>
        <div class="col-lg-9">
            <div class="row">
                <div class="col-lg-7">
                    {$image} {* html content *}
                    <input id="file" type="file" name="img" class="hide" />
                    <div class="dummyfile input-group">
                        <span class="input-group-addon"><i class="icon-file"></i></span>
                        <input id="file-name" type="text" class="disabled" name="filename" readonly />
                        <span class="input-group-btn">
                            <button id="file-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
                                <i class="icon-folder-open"></i> {l s='Choose a file' mod='portfolio'}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#file-selectbutton').click(function(e) {
        $('#file').trigger('click');
    });
    $('#file-name').click(function(e) {
        $('#file').trigger('click');
    });
    $('#file').change(function(e) {
        var val = $(this).val();
        var file = val.split(/[\\/]/);
        $('#file-name').val(file[file.length - 1]);
    });
});
</script>