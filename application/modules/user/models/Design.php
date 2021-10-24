<?php
/**
*	Ccc_Front_Model_Design class
**/
class User_Model_Design
{
	/**
	*	$_defualtTheme protected variable. Used to store default theme name as default.
	*	@var string.
	**/
	protected $_defualtTheme   = 'overcast';
	
	/**
	*	$_themeKey protected variable. Used to store the theme key as front_theme.
	*	@var string.
	**/
	protected $_themeKey        = 'front_theme';
	
	/**
	*	$_defualtTheme protected variable. Used to store theme options.
	*	@var array.
	**/
    protected $_designOption = array();
    
	/**
	*	Get Theme Key Function. Used to get the theme key.
	*	@return themeKey.
	**/
	public function getThemeKey()
	{
		return $this->_themeKey;
	}

	/**
	*	Get Design Function. Used to get the current theme.
	*	@return defualtTheme | themeKey.
	**/
	public function getDesign()
	{
		$seesion = Ccc::getModel("core/session");
		if($seesion->hasSession($this->_themeKey))
		{
			return $seesion->getSession($this->_themeKey);
		}
        return $this->_defualtTheme;
	}
    
	/**
	*	Set Design Function. Used to set the given theme.
	*	@param string $themeName.
	*	@return void.
	**/
    public function setDesign($themeName)
    {
        Ccc::getModel("core/session")->setSession($this->_themeKey, $themeName);
    }
    
    /**
    *  	Get Design Option Function. Used to get theme options.
	*	@return designOption.
    **/
    public function getDesignOption()
    {
        $this->_designOption = array(
            $this->_defualtTheme=> 'Default',
            'black-tie'         => 'Black Tie',
            'blitzer'           => 'Blitzer',
            'cupertino'         => 'Cupertino',
            //'dark-hive'         => 'Dark Hive',
            //'dot-luv'           => 'Dot Luv',
            'eggplant'          => 'Eggplant',
            //'excite-bike'       => 'Excite Bike',
            'flick'             => 'Flick',
            //'hot-sneaks'        => 'Hot Sneaks',
            //'humanity'          => 'Humanity',
            //'le-frog'           => 'Le Frog',
            //'mint-choc'         => 'Mint Choc',
            'overcast'          => 'Overcast',
            'pepper-grinder'    => 'Pepper Grinder',
            'redmond'           => 'Redmond',
            'smoothness'        => 'Smoothness',
            'south-street'      => 'South Street',
            //'start'             => 'Start',
            //'sunny'             => 'Sunny',
            //'swanky-purse'      => 'Swanky Purse',
            //'trontastic'        => 'Trontastic',
            //'ui-darkness'       => 'Ui Darkness',
            //'vader'             => 'Vader'
        );
        
        return $this->_designOption;
    }
}