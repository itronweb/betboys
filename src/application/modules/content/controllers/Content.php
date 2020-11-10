<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

require_once APPPATH . 'config/db.php';
require_once APPPATH . 'config/api_function.php';
require_once APPPATH . 'config/config_api_address.php';

/**
 * Content_field_types Controller
 *
 *
 * @copyright   Copyright (c) 2015
 * @license     MIT License
 *
 */
class Content extends Public_Controller {

    /**
     *
     * @var type
     */
    public $validation_rules = array(
        'submitComment' => array(
            ['field' => 'comment' , 'rules' => 'required|htmlspecialchars|trim' , 'label' => 'متن نظر' ] ,
            ['field' => 'entry' , 'rules' => 'numeric|required' , 'label' => 'پست' ]
        )
    );

    public function __construct () {
        parent::__construct();
        $this->load->eloquent('Content_type');
        $this->load->eloquent('Comment');
        $this->load->eloquent('Content_type_field');
        $this->load->eloquent('Field_type');
        $this->load->eloquent('Field');
        $this->load->eloquent('Field_short');
        $this->load->eloquent('Field_long');
        $this->load->eloquent('Field_int');
        $this->load->eloquent('Entry');
        $this->load->eloquent('Page');
        $this->load->eloquent('Content_category');
		
    }

    public function index () {
        $segments = $this->uri->segments;
        //loading page model to check if page exists
        $this->load->eloquent('settings/Setting');
        $homePageID = Setting::findByCode('homepage')->value;
		
        //نمایش صفحه اول سایت
        if ( count($segments) == 0 ) {
            if ( $page = Page::find($homePageID) ) {
                return $this->showHomePage($page);
            }
            else {
                return $this->__404_error();
            }
        }
        //نمایش صفحات دیگر
        if ( $page = Page::findBySlug(urldecode(implode('/' , $segments))) ) {
            if ( $page->getKey() != $homePageID ) {
//				var_dump('11111111');
//				var_dump($this->showPage($page));
                 return $this->showPage($page);
            }
        }
        //Rendering content type page
        if ( $contentType = Content_type ::findBySlug(urldecode(implode('/' , $segments))) ) {
            return $this->showContentTypePage($contentType);
        }

        //Rendering Entry of content type page
        if ( $contentType = Content_type::findBySlug(urldecode($segments[1])) ) {
            // if entry has category, then the third segment of url is slug of entry,
            // and the sencond is for category
			
            if ( $entry = Entry::findBySlug(urldecode($segments[2])) ) {
                return $this->showEntryPage($contentType , $entry);
            }
            elseif (
                    isset($segments[3]) )
                if ( $entry = Entry::findBySlug(urldecode($segments[3])) and $entry->categories()->get()->count() ) {


                    return
                            $this->showEntryPage($contentType , $entry);
                }
        }
		
        //Rendering category of content type page
        if ( $contentType = Content_type::findBySlug(urldecode($segments[1])) ) {
            if ( $category = Content_category::findBySlug(urldecode($segments[2])) ) {
                return $this->showCategoryPage($contentType , $category);
            }
        }
		
		if( $segments[1] == 'leagueTables' && isset($segments[2])){
			$page = Page::findBySlug(urldecode('leagueTables'));
			return $this->showPage($page,$segments[2]);
		}

        return $this->__404_error();
		
    }

    private function __404_error () {
        $customErrorPageID = Setting::findByCode('custom_error_page')->value;
        $homePageID = Setting::findByCode('homepage')->value;

        if ( $page = Page::find($customErrorPageID) ) {
            if ( $page->getKey() != $homePageID ) {
                return $this->showPage($page);
            }
        }
        else {
            dd('خطای 404- صفحه مورد نظر یافت نشد');
        }
    }

    /**
     * ثبت نظر برای پست ها
     */
    public function submitComment () {

        $this->checkAuth(true);
        $this->load->eloquent('Comment');
        $this->load->eloquent('Entry');
        if ( $this->formValidate(false) ) {
            $entry_id = $this->input->post('entry');
            $data = [

                "comment" => $this->input->post('comment') ,
                "status" => 0 ,
                "user_id" => $this->user->id , "entry_id" => $entry_id ,
            ];
            $slug = Entry::find($entry_id)->slug;
            if ( Comment::create($data) ) {
                $this->message->set_message('نظر شما با موفقیت ثبت گردید و پس از تایید نمایش داده می شود.' , 'success' , 'ارسال نظر' , '/وبلاگ/' . $slug)->redirect();
            }
            else {
                $this->message->set_message('ثبت نظر با مشکل مواجه شد. مجدد تلاش کنید' , 'fail' , 'ارسال نظر' , '/وبلاگ/' . $slug)->redirect();
            }
        }
        else {
            $this->message->set_message('خطا در داده های ورودی. ' . validation_errors() , 'fail' , 'ثبت نظر' , '/وبلاگ/' . $slug)->redirect();
        }
    }

    /**
     * specialy show the home page
     * @param object $page
     */
    public function showHomePage ( $page ) {
        require_once APPPATH . '/modules/slider/models/Slider.php';
        $matchesToday = $this->soccerama->matches()->byDate(date('Y-m-d') , date('2016-12-22'));
//        dd($matchesToday);
        $pageTPL = $page->tpl;
        if ( strpos($pageTPL , '.tpl') ) {
            $pageTPL = str_replace('.tpl' , '' , $pageTPL);
        }
        if ( !$this->smart->moduleTemplateExists('pages/statics/' . $pageTPL . '.tpl') ) {
            $pageTPL = 'default';
        }
//        dd($this->soccerama->matches()->byDate(date('Y-m-d') , date('Y-m-d')));
//        dd($this->soccerama->odds()->byMatchAndBookmakerId(689915));
//        dd($tema);
//        dd($this->soccerama->players()->byId($tema->));

        $todayResults = $this->soccerama->livescores()->byDate(date('2016-12-17'));
//        dd($todayResults);
        $matches = array();
        $odds = array();
        if ( !empty($matchesToday->data) ) {
            foreach ( $matchesToday->data as $key => $match ):
                $home_team = $this->soccerama->teams()->byId($match->home_team_id);
                $away_team = $this->soccerama->teams()->byId($match->away_team_id);
                $temp = $this->soccerama->odds()->byMatchAndBookmakerId($match->id , BOOKMAKER_ID);
                $odds[$key] = $temp->data[0]->types->data[0]->odds->data;
                $matches[$key] = [$home_team , $away_team ];
            endforeach;
            $this->smart->assign([
                'matchesToday' => $matchesToday ,
                'matches' => $matches ,
                'odds' => $odds[0] ,
				'totalStake' => $this->getTotalStake(),
            ]);
        }

        $this->smart->assign(
                array(
                    'title' => 'صفحه اصلی' ,
                    'page' => $page ,
                    'action' => site_url('users/users/register'),
					'totalStake' => $this->getTotalStake(),
		)
			
        );
        $this->smart->view('pages/statics/' . $pageTPL);
    }

    /**
     * Show to static and dynamic pages
     * @param object $page
     */
    public function showPage ( $page , $id = 0 ) {
		//var_dump($id);
		$db = new DB;
		
		if($page->tpl == 'CompetitionTables'){
			
//			$leagues_country = json_decode(file_get_contents(API_DIR . "league/leagues_country.json"));
//			$leagues = json_decode(file_get_contents(API_DIR . "league/leagues.json"));
////				var_dump($leagues_country);
//			if($id != 0 ){
//				foreach($leagues->data as $value){
//					if($value->country_id == $id && $value->season->data->is_current_season == true){
//						
//					}
//						
//				}
//			}
			
			
			if($id != 0){
				
//				$bet = $db->select('leagues','*','leagues_id',$this->user['id']);
				$bet = $db->select('leagues','*','leagues_id',$id);
//				var_dump($bet[0]);
//				$bets = $db->joinTwoTable('bet_form','bets','bets_user_id','user_id','bets_user_id',$this->user['id']);
//				$this->checkAuth(true);
//				$this->load->eloquent('leagues');
//				$this->load->eloquent('league_table');
//
//				$myRecords = Bet::where('user_id' , $this->user->id)->take($this->config->item('per_page'))->skip($this->config->item('per_page') * ($page ))->orderBy('id' , 'desc')->get();
				if(isset($bet[0])){
					$address = new Config_api_address();
				$url = $address->get_address($address->standing,1);
				
				$url = str_replace('{id}',$bet[0]['current_season_id'],$url);
				$content = file_get_contents($url);
				//var_dump($url);
				$content_json = json_decode($content);	
				}
				
				if(isset($content_json) && $content_json->data != null)
					$table = $content_json->data[0]->standings->data;
				else
					$table = null;
				
				$this->smart->assign([
					'show_league_table' => $table ,
	//				'matches' => array_slice($finalObject , 0 , 15) ,
	//				'leagues' => $leagues,
	//				'country' => $country,
	//				'leagues_country' => $leagues_country
	//				'resultsToday' => $resultsToday ,
	//				'totalStake' => $this->getTotalStake(),
	//				'sport' => $sport,
	//				'userTest' => $this->getUser(),
					]);
				
				
			}
			
			if($id==0 || $table == null){
				$league_name = $db->select('league_tables','*');
//				var_dump($league_name);
				$league = array();
				foreach($league_name as $key=>$value){
					$league[$value['continent']][] = $value ;
				}
				
				$this->smart->assign([
					'all_league' => $league
				]);
			}
				
	
			
			if($id!=0 && isset($bet[0])){
				
			}
			
//			$this->smart->view('index');
		}
        
		$pageTPL = $page->tpl;
        if ( strpos($pageTPL , '.tpl') ) {
            $pageTPL = str_replace('.tpl' , '' , $pageTPL);
        }
        if ( !$this->smart->moduleTemplateExists('pages/statics/' . $pageTPL . '.tpl') ) {
            $pageTPL = 'default';
        }
		
        $this->smart->assign(
                array(
                    'page' => $page ,
					'totalStake' => $this->getTotalStake(),
        ));
//		var_dump($page->slug);
		$slug = $page->slug;
		
		$menu_id = $db->select('menus','group_id','target',$slug);
		
		if ( ! empty( $menu_id ) ){
			$group_id = $menu_id[0]['group_id'];
			$query = "SELECT * FROM menus WHERE group_id = '$group_id' ORDER BY weight";
			$menu_group = $db->get_query( $query );
			$this->smart->assign(
                array(
                    'page' => $page ,
					'menu_group' => $menu_group,
        	));
		}
		
        $this->smart->view('pages/statics/' . $pageTPL);
    }

    /**
     * display page for all content types (parameter pass by url)
     * @param object $contentType
     */
    public function showContentTypePage ( $contentType ) {
        $pageTPL = $contentType->list_tpl;
        if ( strpos($pageTPL , '.tpl') ) {
            $pageTPL = str_replace('.tpl' , '' , $pageTPL);
        }
        if ( !$this->smart->moduleTemplateExists('pages/content_types/' . $pageTPL . '.tpl') ) {
            $pageTPL = 'default';
        }
        $this->smart->assign(compact('contentType'));
        $this->smart->addModulePluginsDir('content');
        $this->smart->assign(array( 'entries' ,
            $contentType->entries ,
            'title' =>
            'وبلاگ پیانویاب'
        ));
        $this->smart->assign('entriesCount' , $contentType->entries->count());
        $this->smart->view('pages/content_types/' . $pageTPL);
    }

    /**
     * display page for all categories of a content type (parameter pass by url)
     * @param object $contentType
     * @param object $category
     */ public function showCategoryPage ( $contentType , $category ) {
        $pageTPL = $category->list_tpl;
        if ( strpos($pageTPL , '.tpl') ) {
            $pageTPL = str_replace('.tpl' , '' , $pageTPL);
        }
        if ( !$this->smart->moduleTemplateExists('pages/categories/' . $pageTPL . '.tpl') ) {

            $pageTPL = 'default';
        }
        $this->smart->assign(compact('contentType'));
        $this->smart->assign(compact('category'));
        $this->smart->assign('entriesCount' , $category->entries->count());
        $this->smart->view('pages/categories/' . $pageTPL);
    }

    /**
     * display page for all entries of a content type (parameter pass by url)
     * @param object $contentType
     * @param object $category
     */
    public function showEntryPage ( $contentType , $entry ) {

        $this->load->library('field_handler');
        $this->load->eloquent('Field_short');
        $this->load->eloquent('Field_long');
        $this->load->eloquent('Field_int');
        $this->load->eloquent('Content_type');
        $this->load->eloquent('Entry');
        $this->load->eloquent('Field_type');
        $this->load->eloquent('Field');
        $this->load->eloquent('Content_type_field');
        $this->load->eloquent('Content_category');
        //injecting extra fields to the entry object
        foreach ( $entry->fields as $field ):
            if ( $field->
                    contentTypeField->slug )
                $entry->{$field->contentTypeField->slug} = json_decode($field->valuable->value);
            if ( is_array($entry->{$field->
                            contentTypeField->slug}) )
                $entry->{$field->contentTypeField->slug} = site_url('upload/content/fields/' . implode('' , json_decode($field->valuable->value)));
        endforeach;
        // set the page template
        $pageTPL = $entry->entry_tpl ? $entry->entry_tpl : $contentType->full_tpl;
        if ( strpos($pageTPL , '.tpl') ) {
            $pageTPL = str_replace('.tpl' , '' , $pageTPL);
        }
        if ( !$this->smart->moduleTemplateExists('pages/entries/' . $pageTPL . '.tpl') ) {
            $pageTPL = 'default';
        }

        $Extra_fields = $this->__getExtraFieldObjs($contentType , isset($entry) ? $entry : null);
        $this->smart->assign(compact('contentType'));
        $this->smart->assign(compact('entry'));
        $this->smart->assign(compact('Extra_fields'));
        $this->smart->assign([ 'title' =>
            $entry->title , 'content_type'
            => $contentType ]);

        $this->smart->view('pages/entries/' . $pageTPL);
    }

    /**
     *
     * @param type $content_type
     * @param type $entry

     * @return \Illuminate\Support\Collection
     */
    protected function __getExtraFieldObjs ( $content_type , $entry = null ) {
//Extra Fields
        $Extra_fields = new \Illuminate\Support\Collection ( );
        foreach ( $content_type->fields as $extra_field ) {
            $item = $this->field_handler->getFieldObj($extra_field->fieldType , $extra_field);
            if ( $entry ) {
                $item->setValue($entry->extra_field($extra_field->id));
            }
            if ( $this->input->post($extra_field->slug) != '' ) {
                $item->setNewValue($this->input->post($extra_field->slug));
            }
            elseif ( isset($_FILES[$extra_field->slug]) && $_FILES[$extra_field->slug]['name'] != '' ) {
                $item->setNewValue($_FILES[$extra_field->slug]);
            } $Extra_fields->put($extra_field->slug , $item);
        }

        return $Extra_fields;
    }

}
