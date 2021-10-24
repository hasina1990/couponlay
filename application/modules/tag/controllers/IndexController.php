<?php
class Tag_IndexController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {                             
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {     
        if(!$id = (int)$this->getRequest()->getParam("tag_id", null))
        {
            throw new Exception("invalid request.");
        }
         
        $tag = Ccc::getModel("tag/tag")->fetchRow("tag_id = ".(int)$id);
        if(!$tag)
        {
            throw new Exception("invalid request.");
        }
        
        $tag->view_count = (int)$tag->view_count + 1;
        $tag->updated_date = date("Y-m-d H:i:s");
        $tag->save();
        
        Ccc::getModel("user/session")->setSession("tag", $tag);
        
        $websiteModel     = Ccc::getModel("website/website");
        $select =  $websiteModel->select()
                                ->from(array($websiteModel->getTableName()), array("website_id", "name"))
                                ->where("website_id IN (?)", explode(",", $tag->website_id))
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES);
        
        $websites = $websiteModel->getAdapter()->fetchPairs($select);
        if(!count($websites))
        {
            $websites = array(0);
        }
             
        Ccc::getModel("user/session")->setSession("tagWiseWebsites", $websites);
        
        $block = Ccc::getBlock("tag/index_index");
        
        $codeCount = $block->setCollectionCount()->getCodeCount();
        $dealCount = $block->setCollectionCount()->getDealCount();
        Ccc::getModel("user/session")->setSession("codeCount", $codeCount);
        Ccc::getModel("user/session")->setSession("dealCount", $dealCount);
        Ccc::getModel("user/session")->unsetSession("search");
        
        $this->setTitle($tag->seo_title);
        $this->setMetaTag("description", $tag->seo_description);
        $this->setMetaTag("keywords", $tag->seo_keyword);
        $this->setMetaTag("application-name", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'));
        $this->setMetaTag("application-url", Ccc::getSingleton('config/system_config')->getSystemConfig('website/base/url'));
        $this->getResponse()->appendBody($block->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
}