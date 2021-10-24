<?php
/**
*	Core_Model_Timezone
**/
class Core_Model_Timezone extends Core_Model_Abstract
{
    /**
    *	$_timezones protected Variable. It is used to store value of timezones.
    *	@var array
    **/
	protected $_timezones = array();
    
    /**
    *	$_timezonesOptions protected Variable. It contains options for different timezines.
    *	@var array
    **/
    protected $_timezonesOptions = array();   
     	 
    protected $_timezoneList = array();
    
    protected $_timezoneCollection = array('Australia/Darwin'=>'AUS Central Standard Time','Australia/Sydney'=>'AUS Eastern Standard Time','Asia/Kabul'=>'Afghanistan Standard Time','America/Anchorage'=>'Alaskan Standard Time','Asia/Riyadh'=>'Arab Standard Time','Asia/Dubai'=>'Arabian Standard Time','Asia/Baghdad'=>'Arabic Standard Time','America/Buenos_Aires'=>'Argentina Standard Time','Asia/Yerevan'=>'Armenian Standard Time','America/Halifax'=>'Atlantic Standard Time','Asia/Baku'=>'Azerbaijan Standard Time','Atlantic/Azores'=>'Azores Standard Time','America/Regina'=>'Canada Central Standard Time','Atlantic/Cape_Verde'=>'Cape Verde Standard Time','Australia/Adelaide'=>'Cen. Australia Standard Time','America/Guatemala'=>'Central America Standard Time','Asia/Dhaka'=>'Central Asia Standard Time','America/Manaus'=>'Central Brazilian Standard Time','Europe/Budapest'=>'Central Europe Standard Time','Europe/Warsaw'=>'Central European Standard Time','Pacific/Guadalcanal'=>'Central Pacific Standard Time','America/Chicago'=>'Central Standard Time','America/Mexico_City'=>'Central Standard Time (Mexico)','Asia/Shanghai'=>'China Standard Time','Etc/GMT+12'=>'Dateline Standard Time','Africa/Nairobi'=>'E. Africa Standard Time','Australia/Brisbane'=>'E. Australia Standard Time','Europe/Minsk'=>'E. Europe Standard Time','America/Sao_Paulo'=>'E. South America Standard Time','America/New_York'=>'Eastern Standard Time','Africa/Cairo'=>'Egypt Standard Time','Asia/Yekaterinburg'=>'Ekaterinburg Standard Time','Europe/Kiev'=>'FLE Standard Time','Pacific/Fiji'=>'Fiji Standard Time','Europe/London'=>'GMT Standard Time','Europe/Istanbul'=>'GTB Standard Time','Etc/GMT-3'=>'Georgian Standard Time','America/Godthab'=>'Greenland Standard Time','Atlantic/Reykjavik'=>'Greenwich Standard Time','Pacific/Honolulu'=>'Hawaiian Standard Time','Asia/Calcutta'=>'India Standard Time','Asia/Tehran'=>'Iran Standard Time','Asia/Jerusalem'=>'Israel Standard Time','Asia/Amman'=>'Jordan Standard Time','Asia/Seoul'=>'Korea Standard Time','Indian/Mauritius'=>'Mauritius Standard Time','America/Chihuahua'=>'Mexico Standard Time 2','Atlantic/South_Georgia'=>'Mid-Atlantic Standard Time','Asia/Beirut'=>'Middle East Standard Time','America/Montevideo'=>'Montevideo Standard Time','Africa/Casablanca'=>'Morocco Standard Time','America/Denver'=>'Mountain Standard Time','Asia/Rangoon'=>'Myanmar Standard Time','Asia/Novosibirsk'=>'N. Central Asia Standard Time','Africa/Windhoek'=>'Namibia Standard Time','Asia/Katmandu'=>'Nepal Standard Time','Pacific/Auckland'=>'New Zealand Standard Time','America/St_Johns'=>'Newfoundland Standard Time','Asia/Irkutsk'=>'North Asia East Standard Time','Asia/Krasnoyarsk'=>'North Asia Standard Time','America/Santiago'=>'Pacific SA Standard Time','America/Los_Angeles'=>'Pacific Standard Time','America/Tijuana'=>'Pacific Standard Time (Mexico)','Asia/Karachi'=>'Pakistan Standard Time','Europe/Paris'=>'Romance Standard Time','Europe/Moscow'=>'Russian Standard Time','Etc/GMT+3'=>'SA Eastern Standard Time','America/Bogota'=>'SA Pacific Standard Time','America/La_Paz'=>'SA Western Standard Time','Asia/Bangkok'=>'SE Asia Standard Time','Pacific/Apia'=>'Samoa Standard Time','Asia/Singapore'=>'Singapore Standard Time','Africa/Johannesburg'=>'South Africa Standard Time','Asia/Colombo'=>'Sri Lanka Standard Time','Asia/Taipei'=>'Taipei Standard Time','Australia/Hobart'=>'Tasmania Standard Time','Asia/Tokyo'=>'Tokyo Standard Time','Pacific/Tongatapu'=>'Tonga Standard Time','Etc/GMT+5'=>'US Eastern Standard Time','America/Phoenix'=>'US Mountain Standard Time','America/Caracas'=>'Venezuela Standard Time','Asia/Vladivostok'=>'Vladivostok Standard Time','Australia/Perth'=>'W. Australia Standard Time','Africa/Lagos'=>'W. Central Africa Standard Time','Europe/Berlin'=>'W. Europe Standard Time','Asia/Tashkent'=>'West Asia Standard Time','Pacific/Port_Moresby'=>'West Pacific Standard Time','Asia/Yakutsk'=>'Yakutsk Standard Time');
	
	/**
	*	Get Timezone Offset Function. This function is used to get Timezone Offset.
	*  	@return TimezoneOffset|0.	
	**/
	public function getTimezoneOffset()
	{
		$timezoneId = $this->getTimezone();
		if(array_key_exists($timezoneId, $this->getTimezoneList()))
		{   
			$date = new Zend_Date();
		    $date->setTimezone($timezoneId);
		
			return (int) - $date->getGmtOffset();
			
		}
		return 0;
	}
	
	/**
	*	Get Timezone Function. This function is used to get Timezone object.
	*  	@return object.	
	**/
	public function getTimezone()
	{
		return date_default_timezone_get();
	}
    
	/**
	*	Get Timezone Options Function. This function is used to get Options for Timezone.
	*  	@return Options.	
	**/
    public function getTimezoneOptions()
    {   
       return $this->_timezoneCollection;
		
    }
	
    /**
	*	_Get Territory To Timezone List Function. This function is used to get list of translation from Territory to Timezone.
	*  	@return list.	
	**/
    public function _getTerritoryToTimezoneList()
    {   
        $locale = Ccc::getModel('core/locale');        
        $list = $locale->getTranslationList('territorytotimezone'); 
             
       return  $list; 
    }
    
	/**
	*	_Get Timezones For Country Function. This function is used to get Timezones for specific country.
	*  	@return timezones|null.	
	**/
    public function getTimzonesForCountry($countryId = null)
    {   
            $options = $this->getTimezoneOptions();
            
            $timezones =  $this->_getTerritoryToTimezoneList();
        
            if($countryId)
            {
                foreach($timezones as $_key=>$_value)
                {
                   if($_value!=$countryId)
                   {
                        unset($timezones[$_key]);         
                   }
                } 
            }
            
            $timezonesTemp  = array();
            
            foreach($timezones as $_key=>$_value)
            {
                if(isset($options[$_key]))
                {
                    $timezonesTemp[$_key]= $options[$_key];
                }
                else
                {
                    $timezonesTemp[$_key]= $_key;   
                }
                
            }   
                     
            return $timezonesTemp;
    }
    
    public function getTimezoneList()
    {
        //return $this->_timezoneList['Asia/Culcutta'] = 'Asia/Culcutta';
       /* 
        if(!count($this->_timezoneList))
        {
            $options = $this->getTimezoneOptions();
            
            $timezones =  $this->_getTerritoryToTimezoneList();
            
            foreach($timezones as $_key=>$_value)
            {
                if(isset($options[$_key]))
                {
                    $this->_timezoneList[$_key]= $options[$_key];
                }
                else
                {
                    $this->_timezoneList[$_key]= $_key;   
                }
                
            } 
        }
        
         return $this->_timezoneList;
        */
        return array
            ('Africa/Abidjan'=>'Africa/Abidjan
            ','Africa/Accra'=>'Africa/Accra
            ','Africa/Addis_Ababa'=>'Africa/Addis_Ababa
            ','Africa/Algiers'=>'Africa/Algiers
            ','Africa/Asmera'=>'Africa/Asmera
            ','Africa/Bamako'=>'Africa/Bamako
            ','Africa/Bangui'=>'Africa/Bangui
            ','Africa/Banjul'=>'Africa/Banjul
            ','Africa/Bissau'=>'Africa/Bissau
            ','Africa/Blantyre'=>'Africa/Blantyre
            ','Africa/Brazzaville'=>'Africa/Brazzaville
            ','Africa/Bujumbura'=>'Africa/Bujumbura
            ','Africa/Cairo'=>'Egypt Standard Time
            ','Africa/Casablanca'=>'Morocco Standard Time
            ','Africa/Ceuta'=>'Africa/Ceuta
            ','Africa/Conakry'=>'Africa/Conakry
            ','Africa/Dakar'=>'Africa/Dakar
            ','Africa/Dar_es_Salaam'=>'Africa/Dar_es_Salaam
            ','Africa/Djibouti'=>'Africa/Djibouti
            ','Africa/Douala'=>'Africa/Douala
            ','Africa/El_Aaiun'=>'Africa/El_Aaiun
            ','Africa/Freetown'=>'Africa/Freetown
            ','Africa/Gaborone'=>'Africa/Gaborone
            ','Africa/Harare'=>'Africa/Harare
            ','Africa/Johannesburg'=>'South Africa Standard Time
            ','Africa/Kampala'=>'Africa/Kampala
            ','Africa/Khartoum'=>'Africa/Khartoum
            ','Africa/Kigali'=>'Africa/Kigali
            ','Africa/Kinshasa'=>'Africa/Kinshasa
            ','Africa/Lagos'=>'W. Central Africa Standard Time
            ','Africa/Libreville'=>'Africa/Libreville
            ','Africa/Lome'=>'Africa/Lome
            ','Africa/Luanda'=>'Africa/Luanda
            ','Africa/Lubumbashi'=>'Africa/Lubumbashi
            ','Africa/Lusaka'=>'Africa/Lusaka
            ','Africa/Malabo'=>'Africa/Malabo
            ','Africa/Maputo'=>'Africa/Maputo
            ','Africa/Maseru'=>'Africa/Maseru
            ','Africa/Mbabane'=>'Africa/Mbabane
            ','Africa/Mogadishu'=>'Africa/Mogadishu
            ','Africa/Monrovia'=>'Africa/Monrovia
            ','Africa/Nairobi'=>'E. Africa Standard Time
            ','Africa/Ndjamena'=>'Africa/Ndjamena
            ','Africa/Niamey'=>'Africa/Niamey
            ','Africa/Nouakchott'=>'Africa/Nouakchott
            ','Africa/Ouagadougou'=>'Africa/Ouagadougou
            ','Africa/Porto-Novo'=>'Africa/Porto-Novo
            ','Africa/Sao_Tome'=>'Africa/Sao_Tome
            ','Africa/Tripoli'=>'Africa/Tripoli
            ','Africa/Tunis'=>'Africa/Tunis
            ','Africa/Windhoek'=>'Namibia Standard Time
            ','America/Adak'=>'America/Adak
            ','America/Anchorage'=>'Alaskan Standard Time
            ','America/Anguilla'=>'America/Anguilla
            ','America/Antigua'=>'America/Antigua
            ','America/Araguaina'=>'America/Araguaina
            ','America/Argentina/La_Rioja'=>'America/Argentina/La_Rioja
            ','America/Argentina/Rio_Gallegos'=>'America/Argentina/Rio_Gallegos
            ','America/Argentina/Salta'=>'America/Argentina/Salta
            ','America/Argentina/San_Juan'=>'America/Argentina/San_Juan
            ','America/Argentina/San_Luis'=>'America/Argentina/San_Luis
            ','America/Argentina/Tucuman'=>'America/Argentina/Tucuman
            ','America/Argentina/Ushuaia'=>'America/Argentina/Ushuaia
            ','America/Aruba'=>'America/Aruba
            ','America/Asuncion'=>'America/Asuncion
            ','America/Bahia'=>'America/Bahia
            ','America/Barbados'=>'America/Barbados
            ','America/Belem'=>'America/Belem
            ','America/Belize'=>'America/Belize
            ','America/Blanc-Sablon'=>'America/Blanc-Sablon
            ','America/Boa_Vista'=>'America/Boa_Vista
            ','America/Bogota'=>'SA Pacific Standard Time
            ','America/Boise'=>'America/Boise
            ','America/Buenos_Aires'=>'Argentina Standard Time
            ','America/Cambridge_Bay'=>'America/Cambridge_Bay
            ','America/Campo_Grande'=>'America/Campo_Grande
            ','America/Cancun'=>'America/Cancun
            ','America/Caracas'=>'Venezuela Standard Time
            ','America/Catamarca'=>'America/Catamarca
            ','America/Cayenne'=>'America/Cayenne
            ','America/Cayman'=>'America/Cayman
            ','America/Chicago'=>'Central Standard Time
            ','America/Chihuahua'=>'Mexico Standard Time 2
            ','America/Coral_Harbour'=>'America/Coral_Harbour
            ','America/Cordoba'=>'America/Cordoba
            ','America/Costa_Rica'=>'America/Costa_Rica
            ','America/Cuiaba'=>'America/Cuiaba
            ','America/Curacao'=>'America/Curacao
            ','America/Danmarkshavn'=>'America/Danmarkshavn
            ','America/Dawson'=>'America/Dawson
            ','America/Dawson_Creek'=>'America/Dawson_Creek
            ','America/Denver'=>'Mountain Standard Time
            ','America/Detroit'=>'America/Detroit
            ','America/Dominica'=>'America/Dominica
            ','America/Edmonton'=>'America/Edmonton
            ','America/Eirunepe'=>'America/Eirunepe
            ','America/El_Salvador'=>'America/El_Salvador
            ','America/Fortaleza'=>'America/Fortaleza
            ','America/Glace_Bay'=>'America/Glace_Bay
            ','America/Godthab'=>'Greenland Standard Time
            ','America/Goose_Bay'=>'America/Goose_Bay
            ','America/Grand_Turk'=>'America/Grand_Turk
            ','America/Grenada'=>'America/Grenada
            ','America/Guadeloupe'=>'America/Guadeloupe
            ','America/Guatemala'=>'Central America Standard Time
            ','America/Guayaquil'=>'America/Guayaquil
            ','America/Guyana'=>'America/Guyana
            ','America/Halifax'=>'Atlantic Standard Time
            ','America/Havana'=>'America/Havana
            ','America/Hermosillo'=>'America/Hermosillo
            ','America/Indiana/Knox'=>'America/Indiana/Knox
            ','America/Indiana/Marengo'=>'America/Indiana/Marengo
            ','America/Indiana/Petersburg'=>'America/Indiana/Petersburg
            ','America/Indiana/Tell_City'=>'America/Indiana/Tell_City
            ','America/Indiana/Vevay'=>'America/Indiana/Vevay
            ','America/Indiana/Vincennes'=>'America/Indiana/Vincennes
            ','America/Indiana/Winamac'=>'America/Indiana/Winamac
            ','America/Indianapolis'=>'America/Indianapolis
            ','America/Inuvik'=>'America/Inuvik
            ','America/Iqaluit'=>'America/Iqaluit
            ','America/Jamaica'=>'America/Jamaica
            ','America/Jujuy'=>'America/Jujuy
            ','America/Juneau'=>'America/Juneau
            ','America/Kentucky/Monticello'=>'America/Kentucky/Monticello
            ','America/La_Paz'=>'SA Western Standard Time
            ','America/Lima'=>'America/Lima
            ','America/Los_Angeles'=>'Pacific Standard Time
            ','America/Louisville'=>'America/Louisville
            ','America/Maceio'=>'America/Maceio
            ','America/Managua'=>'America/Managua
            ','America/Manaus'=>'Central Brazilian Standard Time
            ','America/Marigot'=>'America/Marigot
            ','America/Martinique'=>'America/Martinique
            ','America/Mazatlan'=>'America/Mazatlan
            ','America/Mendoza'=>'America/Mendoza
            ','America/Menominee'=>'America/Menominee
            ','America/Merida'=>'America/Merida
            ','America/Mexico_City'=>'Central Standard Time (Mexico)
            ','America/Miquelon'=>'America/Miquelon
            ','America/Moncton'=>'America/Moncton
            ','America/Monterrey'=>'America/Monterrey
            ','America/Montevideo'=>'Montevideo Standard Time
            ','America/Montreal'=>'America/Montreal
            ','America/Montserrat'=>'America/Montserrat
            ','America/Nassau'=>'America/Nassau
            ','America/New_York'=>'Eastern Standard Time
            ','America/Nipigon'=>'America/Nipigon
            ','America/Nome'=>'America/Nome
            ','America/Noronha'=>'America/Noronha
            ','America/North_Dakota/Center'=>'America/North_Dakota/Center
            ','America/North_Dakota/New_Salem'=>'America/North_Dakota/New_Salem
            ','America/Panama'=>'America/Panama
            ','America/Pangnirtung'=>'America/Pangnirtung
            ','America/Paramaribo'=>'America/Paramaribo
            ','America/Phoenix'=>'US Mountain Standard Time
            ','America/Port_of_Spain'=>'America/Port_of_Spain
            ','America/Port-au-Prince'=>'America/Port-au-Prince
            ','America/Porto_Velho'=>'America/Porto_Velho
            ','America/Puerto_Rico'=>'America/Puerto_Rico
            ','America/Rainy_River'=>'America/Rainy_River
            ','America/Rankin_Inlet'=>'America/Rankin_Inlet
            ','America/Recife'=>'America/Recife
            ','America/Regina'=>'Canada Central Standard Time
            ','America/Resolute'=>'America/Resolute
            ','America/Rio_Branco'=>'America/Rio_Branco
            ','America/Santarem'=>'America/Santarem
            ','America/Santiago'=>'Pacific SA Standard Time
            ','America/Santo_Domingo'=>'America/Santo_Domingo
            ','America/Sao_Paulo'=>'E. South America Standard Time
            ','America/Scoresbysund'=>'America/Scoresbysund
            ','America/Shiprock'=>'America/Shiprock
            ','America/St_Barthelemy'=>'America/St_Barthelemy
            ','America/St_Johns'=>'Newfoundland Standard Time
            ','America/St_Kitts'=>'America/St_Kitts
            ','America/St_Lucia'=>'America/St_Lucia
            ','America/St_Thomas'=>'America/St_Thomas
            ','America/St_Vincent'=>'America/St_Vincent
            ','America/Swift_Current'=>'America/Swift_Current
            ','America/Tegucigalpa'=>'America/Tegucigalpa
            ','America/Thule'=>'America/Thule
            ','America/Thunder_Bay'=>'America/Thunder_Bay
            ','America/Tijuana'=>'Pacific Standard Time (Mexico)
            ','America/Toronto'=>'America/Toronto
            ','America/Tortola'=>'America/Tortola
            ','America/Vancouver'=>'America/Vancouver
            ','America/Whitehorse'=>'America/Whitehorse
            ','America/Winnipeg'=>'America/Winnipeg
            ','America/Yakutat'=>'America/Yakutat
            ','America/Yellowknife'=>'America/Yellowknife
            ','Antarctica/Casey'=>'Antarctica/Casey
            ','Antarctica/Davis'=>'Antarctica/Davis
            ','Antarctica/DumontDUrville'=>'Antarctica/DumontDUrville
            ','Antarctica/Mawson'=>'Antarctica/Mawson
            ','Antarctica/McMurdo'=>'Antarctica/McMurdo
            ','Antarctica/Palmer'=>'Antarctica/Palmer
            ','Antarctica/Rothera'=>'Antarctica/Rothera
            ','Antarctica/South_Pole'=>'Antarctica/South_Pole
            ','Antarctica/Syowa'=>'Antarctica/Syowa
            ','Antarctica/Vostok'=>'Antarctica/Vostok
            ','Arctic/Longyearbyen'=>'Arctic/Longyearbyen
            ','Asia/Aden'=>'Asia/Aden
            ','Asia/Almaty'=>'Asia/Almaty
            ','Asia/Amman'=>'Jordan Standard Time
            ','Asia/Anadyr'=>'Asia/Anadyr
            ','Asia/Aqtau'=>'Asia/Aqtau
            ','Asia/Aqtobe'=>'Asia/Aqtobe
            ','Asia/Ashgabat'=>'Asia/Ashgabat
            ','Asia/Baghdad'=>'Arabic Standard Time
            ','Asia/Bahrain'=>'Asia/Bahrain
            ','Asia/Baku'=>'Azerbaijan Standard Time
            ','Asia/Bangkok'=>'SE Asia Standard Time
            ','Asia/Beirut'=>'Middle East Standard Time
            ','Asia/Bishkek'=>'Asia/Bishkek
            ','Asia/Brunei'=>'Asia/Brunei
            ','Asia/Calcutta'=>'India Standard Time
            ','Asia/Choibalsan'=>'Asia/Choibalsan
            ','Asia/Chongqing'=>'Asia/Chongqing
            ','Asia/Colombo'=>'Sri Lanka Standard Time
            ','Asia/Damascus'=>'Asia/Damascus
            ','Asia/Dhaka'=>'Central Asia Standard Time
            ','Asia/Dili'=>'Asia/Dili
            ','Asia/Dubai'=>'Arabian Standard Time
            ','Asia/Dushanbe'=>'Asia/Dushanbe
            ','Asia/Gaza'=>'Asia/Gaza
            ','Asia/Harbin'=>'Asia/Harbin
            ','Asia/Hong_Kong'=>'Asia/Hong_Kong
            ','Asia/Hovd'=>'Asia/Hovd
            ','Asia/Irkutsk'=>'North Asia East Standard Time
            ','Asia/Jakarta'=>'Asia/Jakarta
            ','Asia/Jayapura'=>'Asia/Jayapura
            ','Asia/Jerusalem'=>'Israel Standard Time
            ','Asia/Kabul'=>'Afghanistan Standard Time
            ','Asia/Kamchatka'=>'Asia/Kamchatka
            ','Asia/Karachi'=>'Pakistan Standard Time
            ','Asia/Kashgar'=>'Asia/Kashgar
            ','Asia/Katmandu'=>'Nepal Standard Time
            ','Asia/Krasnoyarsk'=>'North Asia Standard Time
            ','Asia/Kuala_Lumpur'=>'Asia/Kuala_Lumpur
            ','Asia/Kuching'=>'Asia/Kuching
            ','Asia/Kuwait'=>'Asia/Kuwait
            ','Asia/Macau'=>'Asia/Macau
            ','Asia/Magadan'=>'Asia/Magadan
            ','Asia/Makassar'=>'Asia/Makassar
            ','Asia/Manila'=>'Asia/Manila
            ','Asia/Muscat'=>'Asia/Muscat
            ','Asia/Nicosia'=>'Asia/Nicosia
            ','Asia/Novosibirsk'=>'N. Central Asia Standard Time
            ','Asia/Omsk'=>'Asia/Omsk
            ','Asia/Oral'=>'Asia/Oral
            ','Asia/Phnom_Penh'=>'Asia/Phnom_Penh
            ','Asia/Pontianak'=>'Asia/Pontianak
            ','Asia/Pyongyang'=>'Asia/Pyongyang
            ','Asia/Qatar'=>'Asia/Qatar
            ','Asia/Qyzylorda'=>'Asia/Qyzylorda
            ','Asia/Rangoon'=>'Myanmar Standard Time
            ','Asia/Riyadh'=>'Arab Standard Time
            ','Asia/Saigon'=>'Asia/Saigon
            ','Asia/Sakhalin'=>'Asia/Sakhalin
            ','Asia/Samarkand'=>'Asia/Samarkand
            ','Asia/Seoul'=>'Korea Standard Time
            ','Asia/Shanghai'=>'China Standard Time
            ','Asia/Singapore'=>'Singapore Standard Time
            ','Asia/Taipei'=>'Taipei Standard Time
            ','Asia/Tashkent'=>'West Asia Standard Time
            ','Asia/Tbilisi'=>'Asia/Tbilisi
            ','Asia/Tehran'=>'Iran Standard Time
            ','Asia/Thimphu'=>'Asia/Thimphu
            ','Asia/Tokyo'=>'Tokyo Standard Time
            ','Asia/Ulaanbaatar'=>'Asia/Ulaanbaatar
            ','Asia/Urumqi'=>'Asia/Urumqi
            ','Asia/Vientiane'=>'Asia/Vientiane
            ','Asia/Vladivostok'=>'Vladivostok Standard Time
            ','Asia/Yakutsk'=>'Yakutsk Standard Time
            ','Asia/Yekaterinburg'=>'Ekaterinburg Standard Time
            ','Asia/Yerevan'=>'Armenian Standard Time
            ','Atlantic/Azores'=>'Azores Standard Time
            ','Atlantic/Bermuda'=>'Atlantic/Bermuda
            ','Atlantic/Canary'=>'Atlantic/Canary
            ','Atlantic/Cape_Verde'=>'Cape Verde Standard Time
            ','Atlantic/Faeroe'=>'Atlantic/Faeroe
            ','Atlantic/Madeira'=>'Atlantic/Madeira
            ','Atlantic/Reykjavik'=>'Greenwich Standard Time
            ','Atlantic/South_Georgia'=>'Mid-Atlantic Standard Time
            ','Atlantic/St_Helena'=>'Atlantic/St_Helena
            ','Atlantic/Stanley'=>'Atlantic/Stanley
            ','Australia/Adelaide'=>'Cen. Australia Standard Time
            ','Australia/Brisbane'=>'E. Australia Standard Time
            ','Australia/Broken_Hill'=>'Australia/Broken_Hill
            ','Australia/Currie'=>'Australia/Currie
            ','Australia/Darwin'=>'AUS Central Standard Time
            ','Australia/Eucla'=>'Australia/Eucla
            ','Australia/Hobart'=>'Tasmania Standard Time
            ','Australia/Lindeman'=>'Australia/Lindeman
            ','Australia/Lord_Howe'=>'Australia/Lord_Howe
            ','Australia/Melbourne'=>'Australia/Melbourne
            ','Australia/Perth'=>'W. Australia Standard Time
            ','Australia/Sydney'=>'AUS Eastern Standard Time
            ','Etc/GMT'=>'Etc/GMT
            ','Etc/GMT-1'=>'Etc/GMT-1
            ','Etc/GMT-2'=>'Etc/GMT-2
            ','Etc/GMT-3'=>'Georgian Standard Time
            ','Etc/GMT-4'=>'Etc/GMT-4
            ','Etc/GMT-5'=>'Etc/GMT-5
            ','Etc/GMT-6'=>'Etc/GMT-6
            ','Etc/GMT-7'=>'Etc/GMT-7
            ','Etc/GMT-8'=>'Etc/GMT-8
            ','Etc/GMT-9'=>'Etc/GMT-9
            ','Etc/GMT-10'=>'Etc/GMT-10
            ','Etc/GMT-11'=>'Etc/GMT-11
            ','Etc/GMT-12'=>'Etc/GMT-12
            ','Etc/GMT-13'=>'Etc/GMT-13
            ','Etc/GMT-14'=>'Etc/GMT-14
            ','Etc/GMT+1'=>'Etc/GMT+1
            ','Etc/GMT+2'=>'Etc/GMT+2
            ','Etc/GMT+3'=>'SA Eastern Standard Time
            ','Etc/GMT+4'=>'Etc/GMT+4
            ','Etc/GMT+5'=>'US Eastern Standard Time
            ','Etc/GMT+6'=>'Etc/GMT+6
            ','Etc/GMT+7'=>'Etc/GMT+7
            ','Etc/GMT+8'=>'Etc/GMT+8
            ','Etc/GMT+9'=>'Etc/GMT+9
            ','Etc/GMT+10'=>'Etc/GMT+10
            ','Etc/GMT+11'=>'Etc/GMT+11
            ','Etc/GMT+12'=>'Dateline Standard Time
            ','Etc/Unknown'=>'Etc/Unknown
            ','Europe/Amsterdam'=>'Europe/Amsterdam
            ','Europe/Andorra'=>'Europe/Andorra
            ','Europe/Athens'=>'Europe/Athens
            ','Europe/Belgrade'=>'Europe/Belgrade
            ','Europe/Berlin'=>'W. Europe Standard Time
            ','Europe/Bratislava'=>'Europe/Bratislava
            ','Europe/Brussels'=>'Europe/Brussels
            ','Europe/Bucharest'=>'Europe/Bucharest
            ','Europe/Budapest'=>'Central Europe Standard Time
            ','Europe/Chisinau'=>'Europe/Chisinau
            ','Europe/Copenhagen'=>'Europe/Copenhagen
            ','Europe/Dublin'=>'Europe/Dublin
            ','Europe/Gibraltar'=>'Europe/Gibraltar
            ','Europe/Guernsey'=>'Europe/Guernsey
            ','Europe/Helsinki'=>'Europe/Helsinki
            ','Europe/Isle_of_Man'=>'Europe/Isle_of_Man
            ','Europe/Istanbul'=>'GTB Standard Time
            ','Europe/Jersey'=>'Europe/Jersey
            ','Europe/Kaliningrad'=>'Europe/Kaliningrad
            ','Europe/Kiev'=>'FLE Standard Time
            ','Europe/Lisbon'=>'Europe/Lisbon
            ','Europe/Ljubljana'=>'Europe/Ljubljana
            ','Europe/London'=>'GMT Standard Time
            ','Europe/Luxembourg'=>'Europe/Luxembourg
            ','Europe/Madrid'=>'Europe/Madrid
            ','Europe/Malta'=>'Europe/Malta
            ','Europe/Mariehamn'=>'Europe/Mariehamn
            ','Europe/Minsk'=>'E. Europe Standard Time
            ','Europe/Monaco'=>'Europe/Monaco
            ','Europe/Moscow'=>'Russian Standard Time
            ','Europe/Oslo'=>'Europe/Oslo
            ','Europe/Paris'=>'Romance Standard Time
            ','Europe/Podgorica'=>'Europe/Podgorica
            ','Europe/Prague'=>'Europe/Prague
            ','Europe/Riga'=>'Europe/Riga
            ','Europe/Rome'=>'Europe/Rome
            ','Europe/Samara'=>'Europe/Samara
            ','Europe/San_Marino'=>'Europe/San_Marino
            ','Europe/Sarajevo'=>'Europe/Sarajevo
            ','Europe/Simferopol'=>'Europe/Simferopol
            ','Europe/Skopje'=>'Europe/Skopje
            ','Europe/Sofia'=>'Europe/Sofia
            ','Europe/Stockholm'=>'Europe/Stockholm
            ','Europe/Tallinn'=>'Europe/Tallinn
            ','Europe/Tirane'=>'Europe/Tirane
            ','Europe/Uzhgorod'=>'Europe/Uzhgorod
            ','Europe/Vaduz'=>'Europe/Vaduz
            ','Europe/Vatican'=>'Europe/Vatican
            ','Europe/Vienna'=>'Europe/Vienna
            ','Europe/Vilnius'=>'Europe/Vilnius
            ','Europe/Volgograd'=>'Europe/Volgograd
            ','Europe/Warsaw'=>'Central European Standard Time
            ','Europe/Zagreb'=>'Europe/Zagreb
            ','Europe/Zaporozhye'=>'Europe/Zaporozhye
            ','Europe/Zurich'=>'Europe/Zurich
            ','Indian/Antananarivo'=>'Indian/Antananarivo
            ','Indian/Chagos'=>'Indian/Chagos
            ','Indian/Christmas'=>'Indian/Christmas
            ','Indian/Cocos'=>'Indian/Cocos
            ','Indian/Comoro'=>'Indian/Comoro
            ','Indian/Kerguelen'=>'Indian/Kerguelen
            ','Indian/Mahe'=>'Indian/Mahe
            ','Indian/Maldives'=>'Indian/Maldives
            ','Indian/Mauritius'=>'Mauritius Standard Time
            ','Indian/Mayotte'=>'Indian/Mayotte
            ','Indian/Reunion'=>'Indian/Reunion
            ','Pacific/Apia'=>'Samoa Standard Time
            ','Pacific/Auckland'=>'New Zealand Standard Time
            ','Pacific/Chatham'=>'Pacific/Chatham
            ','Pacific/Easter'=>'Pacific/Easter
            ','Pacific/Efate'=>'Pacific/Efate
            ','Pacific/Enderbury'=>'Pacific/Enderbury
            ','Pacific/Fakaofo'=>'Pacific/Fakaofo
            ','Pacific/Fiji'=>'Fiji Standard Time
            ','Pacific/Funafuti'=>'Pacific/Funafuti
            ','Pacific/Galapagos'=>'Pacific/Galapagos
            ','Pacific/Gambier'=>'Pacific/Gambier
            ','Pacific/Guadalcanal'=>'Central Pacific Standard Time
            ','Pacific/Guam'=>'Pacific/Guam
            ','Pacific/Honolulu'=>'Hawaiian Standard Time
            ','Pacific/Johnston'=>'Pacific/Johnston
            ','Pacific/Kiritimati'=>'Pacific/Kiritimati
            ','Pacific/Kosrae'=>'Pacific/Kosrae
            ','Pacific/Kwajalein'=>'Pacific/Kwajalein
            ','Pacific/Majuro'=>'Pacific/Majuro
            ','Pacific/Marquesas'=>'Pacific/Marquesas
            ','Pacific/Midway'=>'Pacific/Midway
            ','Pacific/Nauru'=>'Pacific/Nauru
            ','Pacific/Niue'=>'Pacific/Niue
            ','Pacific/Norfolk'=>'Pacific/Norfolk
            ','Pacific/Noumea'=>'Pacific/Noumea
            ','Pacific/Pago_Pago'=>'Pacific/Pago_Pago
            ','Pacific/Palau'=>'Pacific/Palau
            ','Pacific/Pitcairn'=>'Pacific/Pitcairn
            ','Pacific/Ponape'=>'Pacific/Ponape
            ','Pacific/Port_Moresby'=>'West Pacific Standard Time
            ','Pacific/Rarotonga'=>'Pacific/Rarotonga
            ','Pacific/Saipan'=>'Pacific/Saipan
            ','Pacific/Tahiti'=>'Pacific/Tahiti
            ','Pacific/Tarawa'=>'Pacific/Tarawa
            ','Pacific/Tongatapu'=>'Tonga Standard Time
            ','Pacific/Truk'=>'Pacific/Truk
            ','Pacific/Wake'=>'Pacific/Wake
            ','Pacific/Wallis'=>'Pacific/Wallis'
        );
    }
	public function getTimezoneOffsetCountry($timezoneId = null)
    {
        if(array_key_exists($timezoneId, $this->getTimezoneList()))
        {   
            $date = new Zend_Date();
            $date->setTimezone($timezoneId);
        
            return (int) - $date->getGmtOffset();
        }
        return 0;
    }
    
   
}