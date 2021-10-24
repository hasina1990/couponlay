<?php
/**
*   Core_View_Helper_Widget_Grid class
**/
class Core_View_Block_Widget_Grid extends Core_View_Block_Abstract
{
    protected $_sortColumn      = 'sort';
    protected $_sortDir         = 'dir';
    protected $_sortCssAsc      = 'sort-arrow-asc';
    protected $_sortCssDesc     = 'sort-arrow-desc';
    protected $_sortCssNotSort  = 'not-sort';
    protected $_dir             = array(
                                        'asc'=>'asc',
                                        'desc'=>'desc'
                                    );
    protected $_defaultSort     = 'id';
    protected $_defaultDir      = 'desc';
    protected $_sortAction      = 'grid';
	
	/**
    *	$_recordPerPage protected Variable. Used to store howmany records displayed Per Page.
    *	@var mixed.
    **/
	protected $_recordPerPage = 20;
	
    /**
    *	$_recordPerPageOptions protected Variable. Used to get options that howmany records displayed Per Page.
    *	@var array.
    **/
    protected $_recordPerPageOptions = array(
											10=>10,
											20=>20,
											30=>30,
											50=>50,
											100=>100,
											200=>200
											);
    
    /**
    *	$_pageRange protected Variable. Used to set range to be displayed in pagination.
    *	@var mixed.
    **/
	protected $_pageRange = 5;
	
    /**
    *	$_pageDefault protected Variable. Used to set default page to be displayed as pagination.
    *	@var mixed.
    **/
	protected $_pageDefault = 1;
    
    protected function getSortAction()
    {
        return $this->_sortAction;
    }
    protected function getSortColumn()
    {
        return $this->_sortColumn;
    }
    protected function getSortDirection()
    {
        return $this->_sortDir;
    }
    protected function getDefaultSortColumn()
    {
        return $this->_defaultSort;
    }
    protected function getDefaultSortDirection()
    {
        return $this->_defaultDir;
    }
    protected function getCurrentSortColumn()
    {
        $request=$this->getRequest();
        if($request->getParam($this->getSortColumn()))
            return $request->getParam($this->getSortColumn());

        return NULL;
        
    }
    protected function getCurrentSortDirection()
    {
        $request=$this->getRequest();
        if($request->getParam($this->getSortDirection()))
            return $request->getParam($this->getSortDirection());
        return NULL;
    }
    protected function setOrder($select)
    {
        
        if($this->getCurrentSortColumn() && $this->getCurrentSortDirection())
        {
            return $select->order($this->getCurrentSortColumn().' '.$this->getCurrentSortDirection());
        }
        else
        {
            return $select->order($this->getDefaultSortColumn()." ".$this->getDefaultSortDirection());
        }
    }
	
    protected function getHeaderSortUrl($sort_column=NULL)
    {
        if($sort_column!=NULL && $this->getCurrentSortColumn()==$sort_column)
        {
            if($this->getCurrentSortDirection()==$this->_dir['asc'])
                return $this->url(array('action'=>$this->getSortAction(),$this->getSortColumn()=>$sort_column,$this->getSortDirection()=>$this->_dir['desc']));
            else
                return $this->url(array('action'=>$this->getSortAction(),$this->getSortColumn()=>$sort_column,$this->getSortDirection()=>$this->_dir['asc']));
        }
        if($this->getCurrentSortColumn()==NULL && $sort_column==$this->getDefaultSortColumn())
        {
            if($this->getDefaultSortDirection()==$this->_dir['asc'])
                return $this->url(array('action'=>$this->getSortAction(),$this->getSortColumn()=>$sort_column,$this->getSortDirection()=>$this->_dir['desc']));
        }
        return $this->url(array('action'=>$this->getSortAction(),$this->getSortColumn()=>$sort_column,$this->getSortDirection()=>$this->_dir['asc']));
    }
	
    protected function getHeaderCssClass($column=NULL)
    {
        if($column && $column==$this->getCurrentSortColumn())
        {
            if($this->getCurrentSortDirection()==$this->_dir['asc'])
                return $this->_sortCssAsc;
            else
                return $this->_sortCssDesc;
        }
        else
        {
            if($this->getCurrentSortColumn()==NULL && $column==$this->getDefaultSortColumn())
            {
                if($this->getDefaultSortDirection()==$this->_dir['desc'])
                    return $this->_sortCssDesc;
                else
                    return $this->_sortCssAsc;
            }
            else
            {
                return $this->_sortCssNotSort;
            }
        }
    }
	
    public function getHeaderHtml($label,$column)
    {
        $object = $this->getObject();
        return "<a href='javascript:void(0)'
                 class='".$this->getHeaderCssClass($column)."' ".
                 'onclick="'."$object.setFormURL('".$this->getHeaderSortUrl($column)."').loadPage(); return false;\">".
                 "<span class='sort-title'>".$label."</span>".
                 "</a>";
    }
    
	/**
    *   Get Current Page Function. This fuction is used to get Current Page number.
    *   @return pageDefault|perpage.
    **/
    public function getCurrentPage()
	{
		$front = Zend_Controller_Front::getInstance();
		
		if($perpage = (int)$front->getRequest()->getParam('page'))
		{
			return $perpage;
		}
		
		return $this->_pageDefault;
	}
	
    /**
    *   Get Page Range Function. This fuction is used to get Page Range.
    *   @return pageRange.
    **/
	public function getPageRange()
	{
		return $this->_pageRange;
	}
	
    /**
    *   Get Record Per Page Function. This fuction is used to get howmany Records Per Page.
    *   @return recordPerPage|perpage.
    **/
	public function getRecordPerPage()
	{
		$front = Zend_Controller_Front::getInstance();
		
		if($perpage = (int)$front->getRequest()->getParam('perpage'))
		{
			if(in_array($perpage, $this->getRecordPerPageOption()))
			{
				return $perpage;
			}
		}		
		return $this->_recordPerPage;			
	}
	
    /**
    *   Get Record Per Page Option Function. This fuction is used to get Option of howmany Records Per Page.
    *   @return recordPerPage.
    **/
	public function getRecordPerPageOption()
	{
		return $this->_recordPerPageOptions;
	}

}