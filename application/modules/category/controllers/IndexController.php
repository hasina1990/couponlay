<?php
class Category_IndexController extends Ccc_Controller_Action_Front
{
    public function preDispatch()
    {                             
        parent::preDispatch();
        $this->_setLayout('front');
    }
    
    public function indexAction()
    {     
       
        if(!$id = (int)$this->getRequest()->getParam("category_id", null))
        {
            throw new Exception("invalid request.");
        }
          
        $category = Ccc::getModel("category/category")->fetchRow("category_id = ".(int)$id);
        if(!$category)
        {
            throw new Exception("invalid request.");
        }
        
        $category->view_count = (int)$category->view_count + 1;
        $category->updated_date = date("Y-m-d H:i:s");
        $category->save();
        
        Ccc::getModel("user/session")->setSession("category", $category);
        
        $websiteModel     = Ccc::getModel("website/website");
        $select =  $websiteModel->select()
                                ->from(array($websiteModel->getTableName()), array("website_id", "name"))
                                ->where("FIND_IN_SET(".$category->category_id.", category_id)")
                                ->where("is_enabled = ?", Website_Model_Website::IS_ENABLED_YES);
        
        $websites = $websiteModel->getAdapter()->fetchPairs($select);
        if(!count($websites))
        {
            $websites = array(0);
        }
             
        Ccc::getModel("user/session")->setSession("categoryWiseWebsites", $websites);
        
        $block = Ccc::getBlock("category/index_index");
        
        $codeCount = $block->setCollectionCount()->getCodeCount();
        $dealCount = $block->setCollectionCount()->getDealCount();
        Ccc::getModel("user/session")->setSession("codeCount", $codeCount);
        Ccc::getModel("user/session")->setSession("dealCount", $dealCount);
        Ccc::getModel("user/session")->unsetSession("search");
        
        $this->setTitle($category->seo_title);
        $this->setMetaTag("description", $category->seo_description);
        $this->setMetaTag("keywords", $category->seo_keyword);
        $this->setMetaTag("application-name", Ccc::getSingleton('config/system_config')->getSystemConfig('general/website/seo_title'));
        $this->setMetaTag("application-url", Ccc::getSingleton('config/system_config')->getSystemConfig('website/base/url'));
                              
        $this->getResponse()->appendBody($block->toHtml());
        $this->getHelper('viewRenderer')->setNoRender(true);
    }
}