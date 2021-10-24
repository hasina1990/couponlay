<?php
class Core_Model_Url_Rewrite extends Core_Model_Table_Abstract
{
    protected $_name = 'url_rewrite';
    protected $_primary   = 'id';
    protected $_rowClass = 'Core_Model_Url_Rewrite_Row';
	protected $_rowsetClass = 'Core_Model_Url_Rewrite_Rowset';
    
    const ENTITY_TAG = "tag";
    const ENTITY_STORE = "store";
    const ENTITY_CATEGORY = "category";
    const ENTITY_COUPON = "coupon";
    const ENTITY_PAGE = "page";
    
    public function getRedirectUrlByEntity($id, $entity)
    {
          if($entity == Core_Model_Url_Rewrite::ENTITY_STORE)
          {
              return Ccc::getModel("core/url")->getWebsiteUrl()."/r/s/".$id;
          }
          elseif($entity == Core_Model_Url_Rewrite::ENTITY_COUPON)
          {
              return Ccc::getModel("core/url")->getWebsiteUrl()."/r/".$id;
          }
          
          return Ccc::getModel("core/url")->getWebsiteUrl();
    }
    
    public function getUrlKey($id, $entity)
    {
        $idPath = null;
        if($entity == Core_Model_Url_Rewrite::ENTITY_CATEGORY)
        {
            $idPath = "category/".$id;
        }
        elseif($entity == Core_Model_Url_Rewrite::ENTITY_STORE)
        {
            $idPath = "store/".$id;
        }
        elseif($entity == Core_Model_Url_Rewrite::ENTITY_COUPON)
        {
            $idPath = "coupon/".$id;
        }
        elseif($entity == Core_Model_Url_Rewrite::ENTITY_PAGE)
        {
            $idPath = "page/".$id;
        }
        elseif($entity == Core_Model_Url_Rewrite::ENTITY_TAG)
        {
            $idPath = "tag/".$id;
        }
        
        $select = $this->select()
                       ->from(array($this->getTableName()))
                       ->where("id_path = ?", $idPath);
        
        $urlRewrite = $this->fetchRow($select);
        if(!$urlRewrite)
        {
            return $idPath;
        }
        
        return $urlRewrite->request_path;
    }
    
    public function getRequestUrlByIdPath($entity = null)
    {
        $idPath = null;
        if($entity instanceof Category_Model_Category_Row)
        {
            $idPath = "category/".$entity->category_id;
        }
        elseif($entity instanceof Website_Model_Website_Row)
        {
            $idPath = "store/".$entity->website_id;
        }
        elseif($entity instanceof Coupon_Model_Coupon_Row)
        {
            $idPath = "coupon/".$entity->coupon_id;
        }
        elseif($entity instanceof Page_Model_Page_Row)
        {
            $idPath = "page/".$entity->page_id;
        }
        elseif($entity instanceof Tag_Model_Tag_Row)
        {
            $idPath = "tag/".$entity->tag_id;
        }
        
        $select = $this->select()
                       ->from(array($this->getTableName()))
                       ->where("id_path = ?", $idPath);
        
        $urlRewrite = $this->fetchRow($select);
        if(!$urlRewrite)
        {
            return $idPath;
        }
        
        return $urlRewrite->request_path;
    }
    
    public function saveReWriteURL($entity = null)
    {
        $urlRewrite = $this->createRow();
      
        if($entity instanceof Category_Model_Category_Row)
        {
            $urlRewrite->id_path = "category/".$entity->category_id;
            $urlRewrite->target_path = "category/index/index/category_id/".$entity->category_id;
            $urlRewrite->request_path = $entity->url_key;
        }
        elseif($entity instanceof Website_Model_Website_Row)
        {
            $urlRewrite->id_path = "store/".$entity->website_id;
            $urlRewrite->target_path = "website/index/index/website_id/".$entity->website_id;
            $urlRewrite->request_path = $entity->url_key; 
        }
        elseif($entity instanceof Coupon_Model_Coupon_Row)
        {
            $urlRewrite->id_path = "coupon/".$entity->coupon_id;
            $urlRewrite->target_path = "coupon/index/index/coupon_id/".$entity->coupon_id;
            $urlRewrite->request_path = $entity->url_key; 
        }
        elseif($entity instanceof Page_Model_Page_Row)
        {
            $urlRewrite->id_path = "page/".$entity->page_id;
            $urlRewrite->target_path = "page/index/index/page_id/".$entity->page_id;
            $urlRewrite->request_path = $entity->url_key;
        }
        elseif($entity instanceof Tag_Model_Tag_Row)
        {
            $urlRewrite->id_path = "tag/".$entity->tag_id;
            $urlRewrite->target_path = "tag/index/index/tag_id/".$entity->tag_id;
            $urlRewrite->request_path = $entity->url_key;
        }
        
        $select = $this->select()
                       ->from(array($this->getTableName()))
                       ->where("id_path = ?", $urlRewrite->id_path);
        
        $duplicateRow = $this->fetchRow($select);
        if(!$duplicateRow)
        {                
            $urlRewrite->created_date = date("Y-m-d H:i:s");
            $urlRewrite->save();
        }
        else
        {
            $duplicateRow->target_path =  $urlRewrite->target_path;
            $duplicateRow->request_path =  $urlRewrite->request_path;
            $duplicateRow->updated_date =  date("Y-m-d H:i:s");
            $duplicateRow->save(); 
        }
    }
    
    public function removeUrlByIdPath($entity = null)
    {
        $idPath = null;
        if($entity instanceof Category_Model_Category_Row)
        {
            $idPath = "category/".$entity->category_id;
        }
        elseif($entity instanceof Website_Model_Website_Row)
        {
            $idPath = "store/".$entity->website_id;
        }
        elseif($entity instanceof Coupon_Model_Coupon_Row)
        {
            $idPath = "coupon/".$entity->coupon_id;
        }
        elseif($entity instanceof Page_Model_Page_Row)
        {
            $idPath = "page/".$entity->page_id;
        }
        elseif($entity instanceof Tag_Model_Tag_Row)
        {
            $idPath = "tag/".$entity->tag_id;
        }
        
        $select = $this->select()
                       ->from(array($this->getTableName()))
                       ->where("id_path = ?", $idPath);
        
        $urlRewrite = $this->fetchRow($select);
        if(!$urlRewrite)
        {
            return $this;
        }
        
        $urlRewrite->delete();
        return $this;
    }
}
