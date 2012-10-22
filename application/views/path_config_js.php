var NAT;
if (!NAT) {
    NAT = {};
}  
NAT.BASE_URL = "<?=base_url();?>";
NAT.SITE_URL = "<?=site_url();?>"
NAT.AJAX_URL = "<?=site_url('ajax_api/')?>"
NAT.MAIN_CC_URL = "<?=site_url('main/');?>";