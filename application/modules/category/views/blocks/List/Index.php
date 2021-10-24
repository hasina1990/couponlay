<?php
class Category_View_Block_List_Index extends User_View_Block_Widget_Grid
{   
	public function __construct()                     
	{
        parent::__construct();
		$this->setTemplate('list/index.phtml');
	}

    public function getCategories()
    {
          $categoryModel     = Ccc::getModel("category/category");
          
          $select = $categoryModel->select()
                                ->from(array("C"=>$categoryModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Category_Model_Category::IS_ENABLED_YES)
                                ->order('created_date ASC');
          
         return $categoryModel->fetchAll($select);                        
    }
    
    public function getTagsByCategory($category)
    {
        if(!(int)$category->category_id)
        {
            return null;
        }
        
        $tagModel          = Ccc::getModel("tag/tag"); 
        
        $select = $tagModel->select()
                                ->from(array("T"=>$tagModel->getTableName()),array("*"))
                                ->where("is_enabled = ?", Tag_Model_Tag::IS_ENABLED_YES)
                                ->where("category_id = ?", (int)$category->category_id)
                                ->order('created_date DESC');
        
        return $tagModel->fetchAll($select);
    }
                
    public function getRequestObject()
    {
        $object = Zend_Controller_Front::getInstance();
        return $object;
    }   
}
