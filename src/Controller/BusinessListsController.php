<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
//namespace App\Controller\Admin;
namespace App\Controller;
use App\Controller\AppController;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Email\Email;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;
use Cake\View\Helper\SessionHelper;
use Cake\Controller\Component\PaginatorComponent;
use Cake\Network\Request;
use App\Database\Expression\BetweenComparison;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Datasource\ConnectionManager;

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class BusinessListsController extends AppController {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public $helpers = array('Html');

    public function initialize() {
	
        parent::initialize();
        $this->loadComponent('Flash');
        $this->loadComponent('Paginator');
    }

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->allow(['EditBusinessSearchDoc','SearchDoc', 'delete1', 'RemoveWishlist', 'UpdateDocStatus', 'listing', 'listing2', 'businessDetail', 'ajaxlogin', 'ajaxloginstep1', 'ajaxloginstep2', 'ajaxregister', 'contact', 'wishlist', 'adminUpdateWishlist', 'updateContactSeller', 'deleteimage', 'deletedocument', 'downloadFile', 'sendMailForNotifyUser', 'sendMailForFavariteUser', 'calculateRadius', 'dropfile','add']);
    }

    public function isAuthorized($user) {
        if (isset($user['role_id']) && $user['role_id'] === ADMIN) {
            if (in_array($this->request->action, ['add'])) {
                return false;
            }
            return true;
        }
        if (isset($user['role_id']) && $user['role_id'] === MANAGER) {
            if (in_array($this->request->action, ['add'])) {
                return false;
            }
            return true;
        }
        if (isset($user['role_id']) && $user['role_id'] === BROKER) {
            if (in_array($this->request->action, ['BusinessPlaneAFA', 'BusinessPlaneAll', 'saveSearchAjax', 'deleteDoc', 'uploadDoc', 'add', 'businessList', 'edit', 'view', 'delete', 'city', 'state', 'country', 'subCategory', 'updateSubCategory', 'updateCategory', 'updateStatus', 'businessDetail', 'listing', 'uploadfilestTemp', 'deletefilestTemp', 'uploadimageTemp', 'deletedocument', 'businessWishList', 'businessContactSellerList', 'viewMessage', 'editBusiness', 'historicalSource', 'historicalSourceAll', 'biz_space', 'updateFiInformation'])) {
                return true;
            }
        }
        if (isset($user['role_id']) && $user['role_id'] === BUYER) {
            if (in_array($this->request->action, ['BusinessPlaneAFA', 'BusinessPlaneAll', 'saveSearchAjax', 'deleteDoc', 'uploadDoc', 'add', 'businessList', 'edit', 'view', 'delete', 'city', 'state', 'country', 'subCategory', 'updateSubCategory', 'updateCategory', 'updateStatus', 'businessDetail', 'listing', 'uploadfilestTemp', 'deletefilestTemp', 'uploadimageTemp', 'deletedocument', 'businessWishList', 'businessContactSellerList', 'viewMessage', 'editBusiness', 'historicalSource', 'historicalSourceAll', 'biz_space', 'updateFiInformation'])) {
                return true;
            }
        }
        if (isset($user['role_id']) && $user['role_id'] === SELLER) {
            if (in_array($this->request->action, ['saveSearchAjax', 'BusinessPlaneAFA', 'BusinessPlaneAll', 'deleteDoc', 'uploadDoc', 'add', 'businessList', 'edit', 'view', 'delete', 'city', 'state', 'country', 'subCategory', 'updateSubCategory', 'updateCategory', 'updateStatus', 'businessDetail', 'listing', 'uploadfilestTemp', 'deletefilestTemp', 'uploadimageTemp', 'deletedocument', 'businessWishList', 'businessContactSellerList', 'viewMessage', 'editBusiness', 'historicalSource', 'historicalSourceAll', 'biz_space', 'updateFiInformation'])) {
                return true;
            }
        }
        if (isset($user['role_id']) && $user['role_id'] === ADVISOR) {
            if (in_array($this->request->action, ['saveSearchAjax', 'BusinessPlaneAFA', 'BusinessPlaneAll', 'deleteDoc', 'uploadDoc', 'add', 'businessList', 'edit', 'view', 'delete', 'city', 'state', 'country', 'subCategory', 'updateSubCategory', 'updateCategory', 'updateStatus', 'businessDetail', 'listing', 'uploadfilestTemp', 'deletefilestTemp', 'uploadimageTemp', 'deletedocument', 'businessWishList', 'businessContactSellerList', 'viewMessage', 'editBusiness', 'historicalSource', 'historicalSourceAll', 'biz_space', 'updateFiInformation'])) {
                return true;
            }
        }
        return true;
    }

    /**
     * @List
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function businessList() {
        $this->layout = 'admin';
        $uid = $this->Auth->user('id');
        $roleid = $this->Auth->user('role_id');
        $dataList = TableRegistry::get('BusinessLists');
        try {
            if ($roleid != ADMIN) {
                $data = $this->Paginator->paginate($dataList, [
                    'limit' => Configure::read('pageRecord'),
                    'conditions' => [
                        'BusinessLists.status !=' => DELETED,
                        'BusinessLists.user_id' => $uid
                    ],
                    'order' => ['BusinessLists.id' => 'desc']
                ]);
            } else {
                $data = $this->Paginator->paginate($dataList, [
                    'limit' => Configure::read('pageRecord'),
                    'conditions' => [
                        'BusinessLists.status !=' => DELETED,
                    //'BusinessLists.user_id' => $uid
                    ],
                    'order' => ['BusinessLists.id' => 'desc']
                ]);
            }
        } catch (NotFoundException $e) {

            $page = $this->request->params['paging']['BusinessLists']['page'];
            if ($page >= 1) {
                return $this->redirect(array("page" => $page - 1));
            } else {
                $data = array();
                return $this->redirect(array("page" => $page - 1));
            }
        }
        if ($this->request->is(['post', 'put'])) {
            $search = $this->request->data['search'];
            if ($search) {
                $query = $dataList->query();
                $searchResult = $query->contain(['BusinessGallaries'])->orWhere(['btitle LIKE' => '%' . $search . '%'])->orWhere(['bname LIKE' => '%' . $search . '%'])->orWhere(['btitle LIKE' => '%' . $search . '%'])->where(['status !=' => DELETED]);
                $data = $this->Paginator->paginate($searchResult);
            }
        }
        $this->set('data', $data);
    }

    /**
     * @view
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function view($id = null) {
        $this->layout = 'admin';
        $BussinessList = TableRegistry::get('BusinessLists');
        $businessDocuments = TableRegistry::get('businessDocuments');
        $pageId = base64_decode($id);
        $doclist = array();

        $docquery = $businessDocuments->find('all');
        $docdata = $docquery->where(['bussiness_list_id' => $pageId]);
        foreach ($docdata->toArray() as $doc) {
            $doclist[] = $doc->url;
        }

        $glrylist = array();
        $BusinessGallaries = TableRegistry::get('BusinessGallaries');
        $glryquery = $BusinessGallaries->find('all');
        $glrydata = $glryquery->where(['bussiness_list_id' => $pageId]);
        foreach ($glrydata->toArray() as $glry) {
            $glrylist[] = $glry->url;
        }

        if (empty($pageId)) {
            throw new NotFoundException;
        }
        $data = $BussinessList->get($pageId);
        $this->set('data', $data);
        $this->set('glrylist', $glrylist);
        $this->set('doclist', $doclist);
    }

    /**
     * @add
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function add()
    {
        //pr($this->request->data); die;
        $this->layout = 'admin';
        $User = TableRegistry::get('Users');
        $businessTable = TableRegistry::get('BusinessLists');
        $paymentData = TableRegistry::get('Payments');
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $data = $businessTable->newEntity();
        $userDetail = $User->get($userId, ['contain' => ['UserDetails']]);
        $businesscategories = TableRegistry::get('BusinessCategories');
        $query1 = $businesscategories->query();
        $query = $query1->find('all')->where(['status' => ACTIVE]);
        $cat = $query->all();
        //pr($userDetail);die;

	$this->set('userId',$userId);
        $catlist[0] = 'Select Business Category';
        foreach ($cat->toArray() as $catt) {
            $catlist[$catt->id] = $catt->name;
        }
        $paymentDetail = $paymentData->find('all')
                        ->where(['user_id' => $userId, 'expire_date >' => date('Y-m-d')])->toArray();
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        if ($this->request->is(['post', 'put'])) {
            //pr($this->request->data); die; 
            $action = $this->request->data['action'];
            if ($action == "Delete")
	    {
                if (!empty($this->request->data['documents']))
		{
                    //pr($this->request->data['documents']); die;
                    foreach($this->request->data['documents'] as $val)
                    {
                       $this->dropfile($val);
                       $this->Flash->set('Business Deleted successfully.', ['params' => ['class' => 'alert success']]);
                       return $this->redirect(array('controller' => 'listing', 'action' => 'index'));
                    }
                    
                }
            }
            if ($action == "save")
	    {
                $data1 = $this->request->data;
                foreach ($data1 as $key => $data2) {
                    $data->$key = $data2;
                }
                $dbData = $businessTable->save($data);


		if (!empty($this->request->data['documents']))
		{
		    //pr($this->request->data); die; 
                    $countFile = count($this->request->data['documents']);
                    $documents = $this->request->data['documents'];
		    $docsTitle = $this->request->data['docs_title'];
                   
                    foreach ($documents as $key => $document) {

			$fpd = explode("-",$document);

                        if ($countFile && $fpd[0] == 'files') {
                            
                            //$is_shown_exc = $totalIsShown[$key];
                            $lastId = $dbData->id;
                            $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                            $row = $BusinessDocumentsyTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
                            $row->file_name = $fpd[3];
                            $row->title =  (isset($docsTitle[$key]) && !empty($docsTitle[$key])) ? $docsTitle[$key] : $document;
                            $row->is_shown_exc = 1; // $is_shown_exc;
                            $row->status = ACTIVE;
                            $row->created = date('Y-m-d');
                            $row->modified = date('Y-m-d');
                            $row->user_id = $userId;
                            $row->permissionStage = PRENDA;
                            $res = $BusinessDocumentsyTable->save($row);


                        } else if ($countFile && $fpd[0] !== 'files') {
                            $lastId = $dbData->id;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
			    $row->filename = $fpd[3];
			    $row->title =  (isset($docsTitle[$key]) && !empty($docsTitle[$key])) ? $docsTitle[$key] : $document;
                            $row->status = ACTIVE;
			    $row->file_type = $fpd[0];
                            $res = $BusinessGallariesTable->save($row);
                        } else {
			    $process = false;
                            $this->Flash->set('Document format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
			}

                    }
                }







                $this->Flash->set('Business added successfully.', ['params' => ['class' => 'alert success']]);
                return $this->redirect(array('controller' => 'business_lists', 'action' => 'editBusiness/' . base64_encode($dbData->id)));
            }



            $status = INACTIVE;
            if (!empty($this->request->data['publish']) && $this->request->data['publish'] == 'Publish Your Business') {
                $paymentDetail = $paymentData->find('all')
                                ->where(['user_id' => $userId, 'expire_date >' => date('Y-m-d')])->toArray();
                if (!empty($paymentDetail)) {
                    $status = ACTIVE;
                } else {
                    $status = INACTIVE;
                }
            } elseif (empty($paymentDetail)) {
                $status = INACTIVE;
            }

            $this->request->data['sfinancing_comment'] = isset($this->request->data['sfinancing_comment']) ? $this->request->data['sfinancing_comment'] : '';
            $this->request->data['ssupport_commnet'] = isset($this->request->data['ssupport_commnet']) ? $this->request->data['ssupport_commnet'] : '';
            $this->request->data['scompete_commnet'] = isset($this->request->data['scompete_commnet']) ? $this->request->data['scompete_commnet'] : '';
            $businessTable->patchEntity($data, $this->request->data, ['validate' => 'default']);
            $product = isset($this->request->data['bdproducts_services']) ? $this->request->data['bdproducts_services'] : '';
            $productandserv = serialize($product);
            $data->list_type = $this->request->data['list_type'];
            $data->user_id = $userId;
            $data->status = $status;
            $data->bname_status = 1;
            $data->bloc_status = 1;
            $data->bloc_country = 0;
            $data->bdproducts_services = $productandserv;
            $process = true;
		
	

            $data->bimg = isset($img) ? $img : 0;
            if ($result = $businessTable->save($data)) {
                $process = true;
                if (!empty($this->request->data['documents'])) {
                    $countFile = count($this->request->data['documents']);
                    $documents = $this->request->data['documents'];
                    //$filenameList = isset($this->request->data['filename']) ? $this->request->data['filename'] : '';
                    //$docTilte = isset($this->request->data['docTilte']) ? $this->request->data['docTilte'] : '';
		pr($documents); 
                    foreach ($documents as $key => $document) {

			$fpd = explode($document,"-");
		pr($fpd); die;	
                        if ($countFile && $fpd[0] == 'files') {
                            
                            //$is_shown_exc = $totalIsShown[$key];
                            $lastId = $result->id;
                            $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                            $row = $BusinessDocumentsyTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
                            $row->file_name = $document;
                            $row->title = document;
                            $row->is_shown_exc = 1; // $is_shown_exc;
                            $row->status = ACTIVE;
                            $row->created = date('Y-m-d');
                            $row->modified = date('Y-m-d');
                            $row->user_id = $userId;
                            $row->permissionStage = PRENDA;
                            $res = $BusinessDocumentsyTable->save($row);
                        } else {
                            $process = false;
                            $this->Flash->set('Document format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
                        }
				
			if($countFile && $fpd[0] !== 'files'){
				$lastId = $result->id;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
			    $row->filename = $document;
                            $row->status = ACTIVE;
				$row->file_type = $fpd[0];
                            $res = $BusinessGallariesTable->save($row);
			}else {
			$process = false;
                        $this->Flash->set('Image format not match.', ['params' => ['class' => 'alert error']]);
                        return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
			}



                    }
                }
                if (!empty($this->request->data['myimagebyme'])) {
                    $process = true;
                    $countimgFile = count($this->request->data['myimagebyme']);
                    $totalImage = $this->request->data['myimagebyme'];
                    foreach ($totalImage as $key => $imgFile) {
                        if ($countimgFile) {
                            $string = $this->random_string(10);
                            $img = $imgFile;
                            $lastId = $result->id;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $img;
                            $row->status = ACTIVE;
                            $res = $BusinessGallariesTable->save($row);
                        } else {
                            $process = false;
                            $this->Flash->set('Image format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
                        }
                    }
                }
                if (!empty($this->request->data['publish']) && $this->request->data['publish'] == 'Publish Your Business') {
                    $this->Flash->set('Business added successfully.', ['params' => ['class' => 'alert success']]);
                    return $this->redirect(array('controller' => 'listing', 'action' => 'index'));
                } else {

                    $this->Flash->set('Business added successfully.', ['params' => ['class' => 'alert success']]);
                    return $this->redirect(array('controller' => 'business_lists', 'action' => 'businessDetail/' . base64_encode($result->id)));
                }
            }
            $paymentDetail = $paymentData->find('all')
                            ->where(['user_id' => $userId, 'expire_date >' => date('Y-m-d')])->toArray();
        }

        $state = array();
        $city = array();
        $country = json_decode($this->country());
        $state = json_decode($this->state(223));
        $city = json_decode($this->city());
	
	$HFdata = [];
	
        $this->set(array('data' => $data, 'country' => $country, 'HFdata'=>$HFdata, 'state' => $state, 'city' => $city, 'catlist' => $catlist, 'paymentDetail' => $paymentDetail, 'userDetail' => $userDetail, 'documentlist' => array(), 'contactdata' => array()));
    }
    
    
    
    
    
    
    
    public function addAjax()
    {
        $User = TableRegistry::get('Users');
        $businessTable = TableRegistry::get('BusinessLists');
        $paymentData = TableRegistry::get('Payments');
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
       
        
        if ($this->request->is(['post', 'put']))
        {
                
                if(isset($this->request->data['id']))
                {
                    $iddd = $this->request->data['id'];
                    $data = $businessTable->get($iddd);
                }
                else
                {
                     $data = $businessTable->newEntity();
                }
                
                $data1 = $this->request->data;
                foreach ($data1 as $key => $data2)
                {
                    $data->$key = $data2;
                }
                $dbData = $businessTable->save($data);
                //pr($dbData->id); die; 

		if (!empty($this->request->data['documents']))
		{
                    $countFile = count($this->request->data['documents']);
                    $documents = $this->request->data['documents'];
		    $docsTitle = $this->request->data['docs_title'];
                   
                    foreach ($documents as $key => $document)
                    {
			$fpd = explode("-",$document);
                        if ($countFile && $fpd[0] == 'files')
                        {
                            $lastId = $dbData->id;
                            $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                            $row = $BusinessDocumentsyTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
                            $row->file_name = $fpd[3];
                            $row->title =  (isset($docsTitle[$key]) && !empty($docsTitle[$key])) ? $docsTitle[$key] : $document;
                            $row->is_shown_exc = 1; // $is_shown_exc;
                            $row->status = ACTIVE;
                            $row->created = date('Y-m-d');
                            $row->modified = date('Y-m-d');
                            $row->user_id = $userId;
                            $row->permissionStage = PRENDA;
                            $res = $BusinessDocumentsyTable->save($row);
                        }
                        else if ($countFile && $fpd[0] !== 'files')
                        {
                            $lastId = $dbData->id;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
			    $row->filename = $fpd[3];
			    $row->title =  (isset($docsTitle[$key]) && !empty($docsTitle[$key])) ? $docsTitle[$key] : $document;
                            $row->status = ACTIVE;
			    $row->file_type = $fpd[0];
                            $res = $BusinessGallariesTable->save($row);
                        }
                        else
                        {
			    $process = false;
                            $this->Flash->set('Document format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
			}
                    }
                }
                echo "<input type='hidden' name='id' value='".$dbData->id."' >"; die;  
               
        }
    }
    
    
    
    

    /**
     * @edit
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function edit($id = null) {
        $this->layout = 'admin';
        $pageId = base64_decode($id);
        $userId = $this->Auth->user('id');
        if (empty($pageId)) {
            throw new NotFoundException;
        }
        $businessTable = TableRegistry::get('BusinessLists');
        $data = $businessTable->get($pageId);

        $businesscategories = TableRegistry::get('BusinessCategories');
        $query1 = $businesscategories->query();
        $query = $query1->find('all')->where(['status' => ACTIVE]);
        $cat = $query->all();
        foreach ($cat->toArray() as $catt) {
            $catlist[$catt->id] = $catt->name;
        }
        $sid = $data->bcategory;
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        $subquery = $businessSubcategories->find('all', array('order' => 'name'));
        $subdata = $subquery->where(['category_id' => $sid]);
        foreach ($subdata->toArray() as $subcatt) {
            $subcatlist[$subcatt->id] = $subcatt->name;
        }

        $doclist = array();
        $is_shown_excx = array();
        $BusinessDocumentsTable = TableRegistry::get('BusinessDocuments');
        $docquery = $BusinessDocumentsTable->find('all');
        $docdata = $docquery->where(['bussiness_list_id' => $pageId]);
        foreach ($docdata->toArray() as $doc) {


            $doclist[] = $doc->url;
            $is_shown_excx[] = $doc->is_shown_exc;
        }

        $data->set('myfilesbyme', $doclist);
        $data->set('is_shown_excx', $is_shown_excx);

        $glrylist = array();
        $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
        $glryquery = $BusinessGallariesTable->find('all');
        $glrydata = $glryquery->where(['bussiness_list_id' => $pageId]);
        foreach ($glrydata->toArray() as $glry) {
            $glrylist[] = $glry->url;
        }
        $data->set('myimagebyme', $glrylist);

        $errorInputs = [];
        if ($this->request->is(['post', 'put'])) {
            // pr($this->request->data);
            //   die;

            $list_type = $this->request->data['list_type'];
            $bname_status = isset($this->request->data['bname_status']) ? $this->request->data['bname_status'] : 0;
            $bloc_status = isset($this->request->data['bloc_status']) ? $this->request->data['bloc_status'] : 0;
            $btitle = $this->request->data['btitle'];
            $bcategory = $this->request->data['bcategory'];
            $bsubcategory = $this->request->data['bsubcategory'];
            $bname = $this->request->data['bname'];
            $bloc_address = $this->request->data['bloc_address'];
            $bloc_other_address = isset($this->request->data['bloc_other_address']) ? $this->request->data['bloc_other_address'] : '';
            $bloc_state = $this->request->data['bloc_state'];
            $bloc_city = $this->request->data['bloc_city'];
            $bloc_zip = $this->request->data['bloc_zip'];
            $bloc_year_est = $this->request->data['bloc_year_est'];
            $bnum_employee = $this->request->data['bnum_employee'];
            $bdmarket_overview = $this->request->data['bdmarket_overview'];
            $bdcompetition_postition = $this->request->data['bdcompetition_postition'];
            $bdperformance = $this->request->data['bdperformance'];
            $bdinventory = $this->request->data['bdinventory'];
            $bdequipment = $this->request->data['bdequipment'];
            $bdrei_sale = $this->request->data['bdrei_sale'];
            $sfinancing_status = $this->request->data['sfinancing_status'];
            $sfinancing_comment = isset($this->request->data['sfinancing_comment']) ? $this->request->data['sfinancing_comment'] : 0;
            $ssupport_status = $this->request->data['ssupport_status'];
            $ssupport_commnet = isset($this->request->data['ssupport_commnet']) ? $this->request->data['ssupport_commnet'] : '';
            $scompete_status = $this->request->data['scompete_status'];
            $scompete_commnet = isset($this->request->data['scompete_commnet']) ? $this->request->data['scompete_commnet'] : '';
            $fi_price = $this->request->data['fi_price'];
            $fi_desired_date = $this->request->data['fi_desired_date'];
            $fi_resion_sell = $this->request->data['fi_resion_sell'];
            $fi_revenue = $this->request->data['fi_revenue'];
            $fi_ebitda = $this->request->data['fi_ebitda'];
            $fi_ebit = $this->request->data['fi_ebit'];
            $fi_cashflow = $this->request->data['fi_cashflow'];
            $description = $this->request->data['bddescription'];

            $bdproducts_services = $this->request->data['bdproducts_services'];


            $finalpsdata = array();
            foreach ($bdproducts_services as $psdata) {
                if (!empty($psdata)) {
                    $finalpsdata[] = $psdata;
                }
            }
            $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
            $roleId = $this->Auth->user('role_id');


            // business image start$img
            // business image end
            $todaydate = date('Y-m-d h:i:s');


            $productandserv = serialize($finalpsdata);

            $businessTable = TableRegistry::get('BusinessLists');
            $businessDetaiQuery = $businessTable->find('all')
                    ->where(['BusinessLists.id' => $pageId]);
            $businessdetaildata = count($businessDetaiQuery->toArray());
            if ($businessdetaildata > 0) {
                $query1 = $businessTable->query();
                $query1->update()
                        ->set(['list_type' => $list_type,
                            'bname_status' => $bname_status,
                            'bloc_status' => $bloc_status,
                            'btitle' => $btitle,
                            'bcategory' => $bcategory,
                            'bsubcategory' => $bsubcategory,
                            'bname' => $bname,
                            'bloc_address' => $bloc_address,
                            'bloc_other_address' => $bloc_other_address,
                            'bloc_state' => $bloc_state,
                            'bloc_city' => $bloc_city,
                            'bloc_zip' => $bloc_zip,
                            'bloc_year_est' => $bloc_year_est,
                            'bnum_employee' => $bnum_employee,
                            'bdmarket_overview' => $bdmarket_overview,
                            'bdcompetition_postition' => $bdcompetition_postition,
                            'bdperformance' => $bdperformance,
                            'bdinventory' => $bdinventory,
                            'bdequipment' => $bdequipment,
                            'bdrei_sale' => $bdrei_sale,
                            'sfinancing_status' => $sfinancing_status,
                            'sfinancing_comment' => $sfinancing_comment,
                            'ssupport_status' => $ssupport_status,
                            'ssupport_commnet' => $ssupport_commnet,
                            'scompete_status' => $scompete_status,
                            'scompete_commnet' => $scompete_commnet,
                            'fi_price' => $fi_price,
                            'fi_desired_date' => $fi_desired_date,
                            'fi_resion_sell' => $fi_resion_sell,
                            'fi_revenue' => $fi_revenue,
                            'fi_ebitda' => $fi_ebitda,
                            'fi_ebit' => $fi_ebit,
                            'fi_cashflow' => $fi_cashflow,
                            'bddescription' => $description,
                            'bdproducts_services' => $productandserv,
                            'modified' => $todaydate
                        ])
                        ->where(['id' => $pageId])
                        ->execute();
                ///2222222222222

                $query = $BusinessDocumentsTable->query();
                $query->delete()
                        ->where(['bussiness_list_id' => $pageId])
                        ->execute();

                if (!empty($this->request->data['myfilesbyme'])) {
                    $countFile = count($this->request->data['myfilesbyme']);
                    $totalFile = $this->request->data['myfilesbyme'];
                    $filenameList = $this->request->data['filename'];
                    $totalIsShown = $this->request->data['is_shown_excx'];

                    foreach ($totalFile as $key => $docFile) {
                        if ($countFile) {
                            $img = $docFile;
                            $is_shown = $totalIsShown[$key];

                            $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                            $row = $BusinessDocumentsyTable->newEntity();
                            $row->bussiness_list_id = $pageId;
                            $row->url = $img;
                            $row->file_name = $filenameList[$key];
                            $row->is_shown_exc = $is_shown;
                            $row->status = ACTIVE;
                            $row->created = date('Y-m-d');
                            $row->modified = date('Y-m-d');
                            $row->user_id = $userId;
                            $row->permissionStage = PRENDA;
                            $res = $BusinessDocumentsyTable->save($row);
                        } else {
                            $process = false;
                            $this->Flash->set('Document format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'edit/' . $id));
                        }
                    }
                }
                $query = $BusinessGallariesTable->query();
                $query->delete()
                        ->where(['bussiness_list_id' => $pageId])
                        ->execute();
                if (!empty($this->request->data['myimagebyme'])) {
                    $process = true;
                    $countimgFile = count($this->request->data['myimagebyme']);
                    $totalImage = $this->request->data['myimagebyme'];
                    foreach ($totalImage as $key => $imgFile) {
                        if ($countimgFile) {
                            $string = $this->random_string(10);
                            $img = $imgFile;
                            $lastId = $pageId;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $img;
                            $row->status = ACTIVE;
                            $res = $BusinessGallariesTable->save($row);
                        } else {
                            $process = false;
                            $this->Flash->set('Image format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
                        }
                    }
                }
                $this->Flash->set('Business updated successfully.', ['params' => ['class' => 'alert success']]);
                return $this->redirect(array('controller' => 'business_lists', 'action' => 'edit/' . $id));
            }
        }

        $state = array();
        $city = array();
        $country = json_decode($this->country());
        //$state = json_decode($this->state($data->bloc_country));
        $state = json_decode($this->state(223));
        $city = json_decode($this->city($data->bloc_state));
        $this->set(array('doclist' => $doclist, 'glrylist' => $glrylist, 'data' => $data, 'country' => $country, 'state' => $state, 'city' => $city, 'catlist' => $catlist, 'subcatlist' => $subcatlist));
    }

   


    public function editBusiness($id = null)
    {
	
//pr($this->request->data());die;
        $this->layout = 'admin';
        $pageId = base64_decode($id);
	$businessTable = TableRegistry::get('BusinessLists');
        $userId = $this->Auth->user('id');
	$businessData = $businessTable->find('all')->where(['id'=>$pageId])->toArray();
        if (empty($pageId) || empty($businessData)) {
            throw new NotFoundException;
        }

        
        $paymentData = TableRegistry::get('Payments');
        $User = TableRegistry::get('Users');
        $userDetail = $User->get($userId, ['contain' => ['UserDetails']]);
        $data = $businessTable->get($pageId);
        $businessListEntity = $data;

        $businesscategories = TableRegistry::get('BusinessCategories');
        $query1 = $businesscategories->query();
        $query = $query1->find('all')->where(['status' => ACTIVE]);
        $cat = $query->all();

        foreach ($cat->toArray() as $catt) {
            $catlist[$catt->id] = $catt->name;
        }
        $paymentDetail = $paymentData->find('all')
                        ->where(['user_id' => $userId, 'expire_date >' => date('Y-m-d')])->toArray();
        $sid = $data->bcategory;
        $subcatlist = array();
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        $subquery = $businessSubcategories->find('all', array('order' => 'name'));
        $subdata = $subquery->where(['category_id' => $sid]);
        foreach ($subdata->toArray() as $subcatt) {
            $subcatlist[$subcatt->id] = $subcatt->name;
        }

        $doclist = array();
        $is_shown_excx = array();
        $BusinessDocumentsTable = TableRegistry::get('BusinessDocuments');
        $docquery = $BusinessDocumentsTable->find('all');
        $docdata = $docquery->where(['bussiness_list_id' => $pageId]);
        //$data->set('documentlist',$docdata);

        foreach ($docdata->toArray() as $doc) {
            $doclist[] = $doc;
            $is_shown_excx[] = $doc->is_shown_exc;
        }
        /* $HistoricalFinances = TableRegistry::get('HistoricalFinances');
          $hisquery = $HistoricalFinances->find('all');
          $hisdataIncome = $hisquery->where(['business_list_id' => $pageId])->where(['type' =>1]);
          $empquery = $HistoricalFinances->find('all');
          $hisdataEmp = $empquery->where(['business_list_id' => $pageId])->where(['type' =>2]);
          $offquery = $HistoricalFinances->find('all');
          $hisdataOff = $offquery->where(['business_list_id' => $pageId])->where(['type' =>3]);
         */
        $HistoricalFinances = TableRegistry::get('HistoricalFinancials');
        $HFquery = $HistoricalFinances->find('all');
        $HFdata = $HFquery->where(['business_list_id' => $pageId]);
	$HFdata = $HFdata->toArray();
	$HFVdata = array();
	if($HFdata){
	    $HFID = $HFdata[0]->id;
	    $HistoricalFinancialValues = TableRegistry::get('HistoricalFinancialValues');
	    $HFVquery = $HistoricalFinancialValues->find('all');
	    $HFVdata = $HFVquery->where(['historical_financials_id' => $HFID]);
	    $HFVdata = $HFVdata->toArray();
	}
        $BusinessPlans = TableRegistry::get('BusinessPlans');
        $bpquery = $BusinessPlans->find('all');
        $businessplandataTabOne = $bpquery->where(['bussiness_list_id' => $pageId, 'tab' => 1]);

        $bpquery1 = $BusinessPlans->find('all');
        $businessplandataTabTwo = $bpquery1->where(['bussiness_list_id' => $pageId, 'tab' => 2]);


        $this->set('myfilesbyme', $doclist);
        $data->set('is_shown_excx', $is_shown_excx);

        $glrylist = array();
        $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
        $glryquery = $BusinessGallariesTable->find('all');
        $glrydata = $glryquery->where(['bussiness_list_id' => $pageId]);
        foreach ($glrydata->toArray() as $glry) {
            $glrylist[] = $glry;
        }

        $this->set('myimagebyme', $glrylist);

        $contactSellerList = TableRegistry::get('ContactSellers');
        $contactquery = $contactSellerList->find('all');
        $contactdata = $contactquery->where(['bussiness_list_id' => $pageId])->where(['reciver_id' => $userId])->where(['status' => ACTIVE]);


        $errorInputs = [];
        if ($this->request->is(['post', 'put'])) {
           // pr($this->request->data); die; 
            $action = $this->request->data['action'];

            if ($action == "save") {
                $data1 = $this->request->data;
                $data12 = $businessTable->get($pageId);

                foreach ($data1 as $key => $data2) {
                    $data12->$key = $data2;
                }

                $dbData = $businessTable->save($data12);
		
		if (!empty($this->request->data['documents']))
		{
		    //pr($this->request->data); die; 
                    $countFile = count($this->request->data['documents']);
                    $documents = $this->request->data['documents'];
		    $docsTitle = isset($this->request->data['docs_title'])?$this->request->data['docs_title']:'';
                   
                    foreach ($documents as $key => $document) {

			$fpd = explode("-",$document);

                        if ($countFile && $fpd[0] == 'files') {
                            
                            //$is_shown_exc = $totalIsShown[$key];
                            $lastId = $dbData->id;
                            $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                            $row = $BusinessDocumentsyTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
                            $row->file_name = $fpd[3];
                            $row->title =  (isset($docsTitle[$key]) && !empty($docsTitle[$key])) ? $docsTitle[$key] : $document;
                            $row->is_shown_exc = 1; // $is_shown_exc;
                            $row->status = ACTIVE;
                            $row->created = date('Y-m-d');
                            $row->modified = date('Y-m-d');
                            $row->user_id = $userId;
                            $row->permissionStage = PRENDA;
                            $res = $BusinessDocumentsyTable->save($row);


                        } else if ($countFile && $fpd[0] !== 'files') {
                            $lastId = $dbData->id;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $document;
			    $row->filename = $fpd[3];
			    $row->title =  (isset($docsTitle[$key]) && !empty($docsTitle[$key])) ? $docsTitle[$key] : $document;
                            $row->status = ACTIVE;
			    $row->file_type = $fpd[0];
                            $res = $BusinessGallariesTable->save($row);
                        } else {
			    $process = false;
                            $this->Flash->set('Document format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'editBusiness',base64_encode($dbData->id)));
			}

                    }
                }
		
                $this->Flash->set('Business updated successfully.', ['params' => ['class' => 'alert success']]);
                return $this->redirect(array('controller' => 'business_lists', 'action' => 'editBusiness/' . base64_encode($dbData->id)));
            }


            //$status = INACTIVE;
            //if(!empty($this->request->data['publish']) &&  $this->request->data['publish'] == 'Publish Your Business')
            //{ 
            //$paymentDetail = $paymentData->find('all')
            //			->where(['user_id'=>$userId,'expire_date >' => date('Y-m-d')])->toArray();
            //    if(!empty($paymentDetail)){ 
            //		    $status = ACTIVE;
            //		    
            //    }else{
            //	    $status = INACTIVE;
            //    }
            //}elseif(empty($paymentDetail)){ 
            //		$status = INACTIVE;
            //}


            $list_type = $this->request->data['list_type'];
            $bname_status = isset($this->request->data['bname_status']) ? $this->request->data['bname_status'] : 0;
            $bloc_status = isset($this->request->data['bloc_status']) ? $this->request->data['bloc_status'] : 0;
            $btitle = $this->request->data['btitle'];
            $bcategory = $this->request->data['bcategory'];
            $bsubcategory = $this->request->data['bsubcategory'];
            $bname = $this->request->data['bname'];
            $bloc_address = $this->request->data['bloc_address'];
            $bloc_other_address = isset($this->request->data['bloc_other_address']) ? $this->request->data['bloc_other_address'] : '';
            $bloc_state = $this->request->data['bloc_state'];
            $bloc_city = $this->request->data['bloc_city'];
            $bloc_zip = $this->request->data['bloc_zip'];
            $bloc_year_est = $this->request->data['bloc_year_est'];
            $lat = isset($this->request->data['lat']) ? $this->request->data['lat'] : 0;
            $long = isset($this->request->data['lng']) ? $this->request->data['lng'] : 0;
            $bnum_employee = $this->request->data['bnum_employee'];
            $bdmarket_overview = $this->request->data['bdmarket_overview'];
            $bdcompetition_postition = $this->request->data['bdcompetition_postition'];
            $bdperformance = $this->request->data['bdperformance'];
            $bdinventory = $this->request->data['bdinventory'];
            $bdequipment = $this->request->data['bdequipment'];
            $bdrei_sale = $this->request->data['bdrei_sale'];
            $sfinancing_status = $this->request->data['sfinancing_status'];
            $sfinancing_comment = isset($this->request->data['sfinancing_comment']) ? $this->request->data['sfinancing_comment'] : '';
            $ssupport_status = $this->request->data['ssupport_status'];
            $ssupport_commnet = isset($this->request->data['ssupport_commnet']) ? $this->request->data['ssupport_commnet'] : '';
            $scompete_status = $this->request->data['scompete_status'];
            $scompete_commnet = isset($this->request->data['scompete_commnet']) ? $this->request->data['scompete_commnet'] : '';
            $fi_price = $this->request->data['fi_price'];
            $fi_desired_date = $this->request->data['fi_desired_date'];
            $fi_resion_sell = $this->request->data['fi_resion_sell'];
            $fi_revenue = $this->request->data['fi_revenue'];
            $fi_ebitda = $this->request->data['fi_ebitda'];
            //$fi_ebit   		= $this->request->data['fi_ebit'];
            $fi_cashflow = $this->request->data['fi_cashflow'];
            $description = $this->request->data['bddescription'];

            $bdproducts_services = $this->request->data['bdproducts_services'];
            // 
            //$finalpsdata = array();
            //foreach($bdproducts_services as $psdata){
            //    if(!empty($psdata)){
            //    $finalpsdata[] = $psdata;
            //    }
            //}
            $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
            $roleId = $this->Auth->user('role_id');

            $businessTable = TableRegistry::get('BusinessLists');
            $businessDetaiQuery = $businessTable->find('all')
                            ->where(['BusinessLists.id' => $pageId])->toArray();
            $businessdetaildata = count($businessDetaiQuery);
            if ($businessdetaildata > 0) {
                if (!empty($businessDetaiQuery)) {
                    $previousPrice = $businessDetaiQuery[0]->fi_price;
                    if ($previousPrice != $fi_price) {
                        $wishlists = TableRegistry::get('Wishlists');
                        $query = $wishlists->find('all');
                        $newdata = $query->where(['bussiness_list_id' => $pageId])->toArray();
                        if (!empty($newdata)) {
                            foreach ($newdata as $wishUser) {
                                $wUserId = $wishUser->user_id;
                                $this->sendMailForFavariteUser($pageId, $wUserId);
                            }
                        }
                    }
                }
                //pr($this->request->data); die; 
                $todaydate = date('Y-m-d h:i:s');
                //pr($this->request->data); die; 
                //$query1 = $businessTable->query();
                //		$query1->update()
                //			->set(['list_type' =>  $list_type,
                //				   'bname_status'=>$bname_status,
                //				   'status'=> $status,
                //				   'bloc_status'=>$bloc_status,
                //				   'btitle'=>$btitle,
                //				   'bcategory'=>$bcategory,
                //				   'bsubcategory'=>$bsubcategory,
                //				   'bname'=>$bname,
                //				   'bloc_address'=>$bloc_address,
                //				   'bloc_other_address'=>$bloc_other_address,
                //				   'bloc_state'=>$bloc_state,
                //				   'bloc_city'=>$bloc_city,
                //				   'bloc_zip'=>$bloc_zip,
                //				   'bloc_year_est'=>$bloc_year_est,
                //				   'bnum_employee'=>$bnum_employee,
                //				   'bdmarket_overview'=>$bdmarket_overview,
                //				   'bdcompetition_postition'=>$bdcompetition_postition,
                //				   'bdperformance'=>$bdperformance,
                //				   'bdinventory'=>$bdinventory,
                //				   'bdequipment'=>$bdequipment,
                //				   'bdrei_sale'=>$bdrei_sale,
                //				   'sfinancing_status'=>$sfinancing_status, 
                //				   'sfinancing_comment'=>$sfinancing_comment, 
                //				   'ssupport_status'=>$ssupport_status, 
                //				   'ssupport_commnet'=>$ssupport_commnet, 
                //				   'scompete_status'=>$scompete_status, 
                //				   'scompete_commnet'=>$scompete_commnet, 
                //				   'fi_price'=>$fi_price,
                //				   'fi_desired_date'=>$fi_desired_date,
                //				   'fi_resion_sell'=>$fi_resion_sell,
                //				   'fi_revenue'=>$fi_revenue,
                //				   'fi_ebitda'=>$fi_ebitda,
                //				   'fi_ebit'=>$fi_ebit,
                //				   'fi_cashflow'=>$fi_cashflow,
                //				   'bddescription'=>$description,
                //				   'bdproducts_services'=>$productandserv,
                //				   'lat'=>$lat,
                //				   'lng'=>$long,
                //				   'modified' =>$todaydate
                //				   ])
                //			->where(['id' => $pageId])
                //			->execute();
                ///2222222222222

                $query = $BusinessDocumentsTable->query();
                $query->delete()
                        ->where(['bussiness_list_id' => $pageId])
                        ->execute();

                if (!empty($this->request->data['myfilesbyme'])) {
                    $countFile = count($this->request->data['myfilesbyme']);
                    $totalFile = $this->request->data['myfilesbyme'];
                    $filenameList = isset($this->request->data['filename']) ? $this->request->data['filename'] : '';
                    $docTilte = isset($this->request->data['docTilte']) ? $this->request->data['docTilte'] : '';
                    //$totalIsShown = $this->request->data['is_shown_excx'];

                    foreach ($totalFile as $key => $docFile) {
                        if ($countFile) {
                            if ($docFile) {
                                $img = str_replace(' ', '_', $docFile);
                                //$is_shown = $totalIsShown[$key];

                                $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                                $row = $BusinessDocumentsyTable->newEntity();
                                $row->bussiness_list_id = $pageId;
                                $row->url = $img;
                                $row->file_name = isset($filenameList[$key]) ? $filenameList[$key] : '';
                                $row->title = isset($docTilte[$key]) ? $docTilte[$key] : '';
                                $row->is_shown_exc = 1; //$is_shown;
                                $row->status = ACTIVE;
                                $row->created = date('Y-m-d');
                                $row->modified = date('Y-m-d');
                                $row->user_id = $userId;
                                $row->permissionStage = PRENDA;
                                $res = $BusinessDocumentsyTable->save($row);
                            }
                        } else {
                            $process = false;
                            $this->Flash->set('Document format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'editBusiness/' . $id));
                        }
                    }
                    /// send mail for exchange space user if notificatin true
                    $this->sendMailForNotifyUser($pageId, $userId);
                }
                $query = $BusinessGallariesTable->query();
                $query->delete()
                        ->where(['bussiness_list_id' => $pageId])
                        ->execute();
                if (!empty($this->request->data['myimagebyme'])) {
                    $process = true;
                    $countimgFile = count($this->request->data['myimagebyme']);
                    $totalImage = $this->request->data['myimagebyme'];
                    foreach ($totalImage as $key => $imgFile) {
                        if ($countimgFile) {
                            $string = $this->random_string(10);
                            $img = $imgFile;
                            $lastId = $pageId;
                            $BusinessGallariesTable = TableRegistry::get('BusinessGallaries');
                            $row = $BusinessGallariesTable->newEntity();
                            $row->bussiness_list_id = $lastId;
                            $row->url = $img;
                            $row->status = ACTIVE;
                            $res = $BusinessGallariesTable->save($row);
                        } else {
                            $process = false;
                            $this->Flash->set('Image format not match.', ['params' => ['class' => 'alert error']]);
                            return $this->redirect(array('controller' => 'business_lists', 'action' => 'add'));
                        }
                    }
                }
            }
            $paymentDetail = $paymentData->find('all')
                            ->where(['user_id' => $userId, 'expire_date >' => date('Y-m-d')])->toArray();
            //$saveAction =  $this->request->data['action'];
            $session = $this->request->session();
            //    if($saveAction == 2){
            //	$session->write('SaveActione', 2);
            //    }else{
            //	$session->write('SaveActione', 1);
            //    }
            $this->Flash->set('Business updated successfully.', ['params' => ['class' => 'alert success']]);
            return $this->redirect(array('controller' => 'business_lists', 'action' => 'editBusiness/' . $id));
        }

        $state = array();
        $city = array();
        $country = json_decode($this->country());
        //$state = json_decode($this->state($data->bloc_country));
        $state = json_decode($this->state(223));
        $city = json_decode($this->city($data->bloc_state));
        //pr($data);
	//	$this->set(array('bussinessId'=>$pageId,'contactdata'=>$contactdata,'hisdataIncome'=>$hisdataIncome,'hisdataEmp'=>$hisdataEmp,'hisdataOff'=>$hisdataOff,'documentlist'=>$docdata,'doclist'=>$doclist,'glrylist'=>$glrylist,'data'=>$data,'country'=>$country,'state'=>$state,'city'=>$city,'catlist'=>$catlist,'subcatlist'=>$subcatlist,'paymentDetail'=>$paymentDetail,'userDetail'=>$userDetail));
        $this->set(array('bussinessId' => $pageId, 'contactdata' => $contactdata,'HFVdata'=>$HFVdata, 'HFdata' => $HFdata, 'businessplandataTabOne' => $businessplandataTabOne, 'businessplandataTabTwo' => $businessplandataTabTwo, 'documentlist' => $docdata, 'doclist' => $doclist, 'glrylist' => $glrylist, 'data' => $data, 'country' => $country, 'state' => $state, 'city' => $city, 'catlist' => $catlist, 'subcatlist' => $subcatlist, 'paymentDetail' => $paymentDetail, 'userDetail' => $userDetail));
    }

    /**
     * @Update Status
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateStatus() {
        $businessTable = TableRegistry::get('BusinessLists');
        if ($this->request->is(['post', 'put'])) {
            $atID = $this->request->data['atID'];
            $status = $this->request->data['setStatus'];
            $data = $businessTable->get($atID);
            if (empty($data)) {
                throw new NotFoundException;
            }
            $data->status = $status;
            $businessTable->save($data);
            echo "Bussiness updated successfully.";
            exit;
        }
    }

    /**
     * @delete
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function delete() {
        $businessTable = TableRegistry::get('BusinessLists');
        $id = $_POST['ID'];
        $pageId = base64_decode($id);
        if (empty($pageId)) {
            throw new NotFoundException;
        }
        if ($this->request->is(['post', 'put'])) {
            $data = $businessTable->get($pageId);
            $data->status = DELETED;
            $businessTable->save($data);
            echo "Business deleted successfully.";
            exit;
        }
    }

    public function delete1($id = null) {
        $businessTable = TableRegistry::get('BusinessLists');
        //$id = $_POST['ID'];
        $pageId = base64_decode($id);
        if (empty($pageId)) {
            throw new NotFoundException;
        }

        $data = $businessTable->get($pageId);
        $data->status = DELETED;
        if ($businessTable->save($data)) {
            $this->Flash->success('Business deleted successfully', ['params' => ['class' => 'alert alert-success']]);
            return $this->redirect(array('controller' => 'listing', "action" => 'index'));
        } else {
            $this->Flash->error('Something went wrong', ['params' => ['class' => 'alert alert-error']]);
            return $this->redirect(array("action" => 'editBusiness', $id));
            //return $this->redirect( array( "action"=>'' ) );	
        }
    }

    /**
     * @delete all
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deleteall() {
        $businessTable = TableRegistry::get('BusinessLists');
        $ids = $_POST['ids'];
        //$pageId = base64_decode($id);
        if (empty($ids)) {
            throw new NotFoundException;
        }
        if ($this->request->is(['post', 'put'])) {
            foreach ($ids as $id) {
                $data = $businessTable->get($id);
                $data->status = DELETED;
                $businessTable->save($data);
            }
            echo "Record deleted successfully.";

            exit;
        }
    }

    /**
     * @country 
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function country() {
        $this->loadModel('Countries');
        $query = $this->Countries->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'country_name'])->order(['country_name' => 'ASC']);
        $country = $query->all();
        return json_encode($country);
        exit;
    }

    /**
     * @states 
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function state($id = null) {
        $responsetype = 'main';
        if (isset($this->request->data['cid'])) {
            $id = $this->request->data['cid'];
            $responsetype = 'ajax';
        }
        $this->loadModel('States');
        $query = $this->States->find('list', [
                    'keyField' => 'region_id',
                    'valueField' => 'region_name'])
                ->where(['States.country_id' => $id])
                ->order(['region_name' => 'ASC']);
        $state = $query->all();
        if ($responsetype == 'ajax') {
            echo json_encode($state);
        } else {
            return json_encode($state);
        }
        exit;
    }

    /**
     * @city 
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function city($id = null) {
        $this->loadModel('Cities');
        $responsetype = 'main';

        if (isset($this->request->data['sid'])) {
            $id = $this->request->data['sid'];
            $responsetype = 'ajax';
        }

        $query = $this->Cities->find('list', [
                    'keyField' => 'cty_id',
                    'valueField' => 'name'])
                ->where(['Cities.sta_id' => $id])
                ->order(['name' => 'ASC']);
        $city = $query->all();

        if ($responsetype == 'ajax') {
            echo json_encode($city);
        } else {
            return json_encode($city);
        }

        exit;
    }

    /**
     * @random_string 
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }

    /**
     * @view cat list
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function businessCategoryList() {
        $this->layout = 'admin';
        $BusinessCategories = TableRegistry::get('BusinessCategories');
        try {
            $data = $this->Paginator->paginate($BusinessCategories, [
                'limit' => Configure::read('pageRecord'),
                'conditions' => [
                    'BusinessCategories.status !=' => DELETED,
                ],
                'order' => ['BusinessCategories.id' => 'desc']
            ]);
        } catch (NotFoundException $e) {
            $page = $this->request->params['paging']['BusinessCategories']['page'];
            if ($page >= 1) {
                return $this->redirect(array("page" => $page - 1));
            } else {
                $data = array();
                return $this->redirect(array("page" => $page - 1));
            }
        }
        $this->set('data', $data->toArray());
    }

    /**
     * @view business Sub Category list
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function businessSubCategorylist() {
        $this->layout = 'admin';
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        try {
            $data = $this->Paginator->paginate($businessSubcategories, [
                'limit' => Configure::read('pageRecord'),
                'conditions' => [
                    'BusinessSubcategories.status !=' => DELETED,
                ],
                'order' => ['BusinessSubcategories.id' => 'desc']
            ]);
        } catch (NotFoundException $e) {
            $page = $this->request->params['paging']['BusinessSubcategories']['page'];
            if ($page >= 1) {
                return $this->redirect(array("page" => $page - 1));
            } else {
                $data = array();
                return $this->redirect(array("page" => $page - 1));
            }
        }
        $this->set('data', $data->toArray());
    }

    /**
     * @add Category
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function addCategory() {
        $this->layout = 'admin';
        $BusinessCategories = TableRegistry::get('BusinessCategories');


        if ($this->request->is(['post', 'put'])) {

            $businessCatEntity = $BusinessCategories->newEntity();
            $businessCatEntity->name = $this->request->data['name'];
            $businessCatEntity->status = $this->request->data['status'];
            $BusinessCategories->save($businessCatEntity);
            $this->Flash->success('Record add successfully.', ['params' => ['class' => 'alert alert-success']]);
            return $this->redirect(['action' => 'businessCategoryList']);
        }
    }

    /**
     * @add Sub Category
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function addSubCategory() {
        $this->layout = 'admin';
        $BusinessCategories = TableRegistry::get('BusinessCategories');
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');

        $query1 = $BusinessCategories->query();
        $query = $query1->find('all')->where(['status' => ACTIVE]);
        $cat = $query->all();
        foreach ($cat->toArray() as $catt) {
            $catlist[$catt->id] = $catt->name;
        }
        if ($this->request->is(['post', 'put'])) {
            $businesssubCatEntity = $businessSubcategories->newEntity();
            $businesssubCatEntity->name = $this->request->data['name'];
            $businesssubCatEntity->status = $this->request->data['status'];
            $businesssubCatEntity->category_id = $this->request->data['category_id'];
            $businesssubCatEntity->created = date('Y-m-d h:i:s');
            $businesssubCatEntity->modified = date('Y-m-d h:i:s');
            $businessSubcategories->save($businesssubCatEntity);
            $this->Flash->success('Record add successfully.', ['params' => ['class' => 'alert alert-success']]);
            return $this->redirect(['action' => 'businessSubCategorylist']);
        }
        $this->set('cat', $catlist);
    }

    /**
     * @Delete Category
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deleteCategory() {
        $businessCategories = TableRegistry::get('BusinessCategories');
        $id = $_POST['ID'];
        $menuId = base64_decode($id);
        if (empty($menuId)) {
            throw new NotFoundException;
        }
        if ($this->request->is(['post', 'put'])) {
            $data = $businessCategories->get($menuId);
            $data->status = DELETED;
            $businessCategories->save($data);
            echo "Record deleted successfully.";
            exit;
        }
    }

    /**
     * @Delete sub Category
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deleteSubCategory() {
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        $id = $_POST['ID'];
        $menuId = base64_decode($id);
        if (empty($menuId)) {
            throw new NotFoundException;
        }
        if ($this->request->is(['post', 'put'])) {
            $data = $businessSubcategories->get($menuId);
            $data->status = DELETED;
            $businessSubcategories->save($data);
            echo "Record deleted successfully.";
            exit;
        }
    }

    /**
     * @Update Category Status
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateCategoryStatus() {
        $businessCategories = TableRegistry::get('BusinessCategories');
        if ($this->request->is(['post', 'put'])) {
            $atID = $this->request->data['atID'];
            $status = $this->request->data['setStatus'];
            $data = $businessCategories->get($atID);
            if (empty($data)) {
                throw new NotFoundException;
            }
            $data->status = $status;
            $businessCategories->save($data);
            echo "Record updated successfully.";
            exit;
        }
    }

    /**
     * @Update Sub Category Status
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateSubCategoryStatus() {
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        if ($this->request->is(['post', 'put'])) {
            $atID = $this->request->data['atID'];
            $status = $this->request->data['setStatus'];
            $data = $businessSubcategories->get($atID);
            if (empty($data)) {
                throw new NotFoundException;
            }
            $data->status = $status;
            $businessSubcategories->save($data);
            echo "Record updated successfully.";
            exit;
        }
    }

    /**
     * @edit Categories
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function editCategory($id = null) {
        $this->layout = 'admin';
        $businessCategories = TableRegistry::get('BusinessCategories');
        $id = base64_decode($id);
        $data = $businessCategories->get($id);
        $this->set('data', $data);
    }

    /**
     * @edit Sub Category
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function editSubCategory($id = null) {
        $this->layout = 'admin';
        $BusinessCategories = TableRegistry::get('BusinessCategories');
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');

        $query1 = $BusinessCategories->query();
        $query = $query1->find('all')->where(['status' => ACTIVE]);
        $cat = $query->all();
        foreach ($cat->toArray() as $catt) {
            $catlist[$catt->id] = $catt->name;
        }

        $id = base64_decode($id);
        $data = $businessSubcategories->get($id);
        $this->set('data', $data);
        $this->set('cat', $catlist);
    }

    /**
     * @update Categories
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateCategory() {
        $this->autoRender = false;
        $businessCategories = TableRegistry::get('BusinessCategories');
        $id = $this->request->data['id'];
        $name = $this->request->data['name'];
        $status = $this->request->data['status'];

        $data = $businessCategories->get($id);
        $data->name = $name;
        $data->status = $status;
        $businessCategories->save($data);
        $this->Flash->success('Record updated successfully.', ['params' => ['class' => 'alert alert-success']]);
        return $this->redirect(['action' => 'editCategory/' . base64_encode($id)]);
    }

    /**
     * @update Sub Category
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateSubCategory() {
        $this->autoRender = false;
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        $id = $this->request->data['id'];
        $name = $this->request->data['name'];
        $status = $this->request->data['status'];
        $category_id = $this->request->data['category_id'];

        $data = $businessSubcategories->get($id);
        $data->name = $name;
        $data->status = $status;
        $data->category_id = $category_id;
        $businessSubcategories->save($data);
        $this->Flash->success('Record updated successfully.', ['params' => ['class' => 'alert alert-success']]);
        return $this->redirect(['action' => 'editSubCategory/' . base64_encode($id)]);
    }

    /**
     * @update sub Category list for ajax
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function subCategory() {
        $this->autoRender = false;
        $businessSubcategories = TableRegistry::get('BusinessSubcategories');
        $id = $this->request->data['cid'];
        $query = $businessSubcategories->find('all', array('order' => 'name'));
        $data = $query->where(['category_id' => $id])->where(['status' => ACTIVE]);

        foreach ($data->toArray() as $subcatt) {
            $subcatlist[$subcatt->id] = $subcatt->name;
        }
        echo json_encode($subcatlist);
        exit;
    }

  

    /**
     * @Business preview page when add business
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function businessDetail($id = null) {
       $this->layout = 'userProfile';
        $title = "Business Details";
        $session = $this->request->session();

        if ($this->request->is(['post', 'put'])) {
            $session->write('Config.busDataPreview', "");
            $busDataPreview = $this->request->data;
            $session->write('Config.busDataPreview', $busDataPreview);
            exit;
        }

        if ($id) {

            $businessTable = TableRegistry::get('BusinessLists');
            $pageId = base64_decode($id);
            $glrylist = array();
            $BusinessGallaries = TableRegistry::get('BusinessGallaries');
            $glryquery = $BusinessGallaries->find('all');
            $glrydata = $glryquery->where(['bussiness_list_id' => $pageId]);
            foreach ($glrydata->toArray() as $glry) {
                $glrylist[] = $glry->url;
            }

            $BusinessDocuments = TableRegistry::get('BusinessDocuments');
            $docquery = $BusinessDocuments->find('all');
            $docdata = $docquery->where(['bussiness_list_id' => $pageId]);
            foreach ($docdata->toArray() as $doc) {
                $doclist[] = $doc->url;
            }

            if (empty($pageId)) {
                throw new NotFoundException;
            }
            $data = $businessTable->find('all', array('conditions' => array('id' => $pageId)))->first()->toArray();
           
            if ($data) {
                $uid = $data['user_id'];
                $userTable = TableRegistry::get('Users');
                $users = $userTable->get($uid, ['contain' => ['UserDetails']]);
            }
            $this->set(compact('data', 'users', 'doclist', 'title'));
            $this->set('glrylist', $glrylist);
        } else {
            $data = "";
            $data = $session->read('Config.busDataPreview');
            $businessTable = TableRegistry::get('BusinessLists');
             //echo $data['user_id']; die; 
            $pageId = $data['id'];
            $glrylist = array();
            $BusinessGallaries = TableRegistry::get('BusinessGallaries');
            $glryquery = $BusinessGallaries->find('all');
            $glrydata = $glryquery->where(['bussiness_list_id' => $pageId]);
            foreach ($glrydata->toArray() as $glry) {
                $glrylist[] = $glry->url;
            }
	     $AdminId = $this->Auth->user('id');
	   
            $userTable = TableRegistry::get('Users');
            $users = $userTable->get($AdminId,['contain'=>['UserDetails']]);
            $userdata['oldimgname'] = $users['user_detail']['profile_picture'];
	   //$usersdetails = $userTable->get($data['user_id'], ['contain' => ['UserDetails']]);
            $this->set(['data'=>$data,'userdata'=>$userdata,'users'=>$users,'glrylist'=>$glrylist]);
        } //pr($data); die; 
	//pr($users);die;
    }

    /**
     * @Business Listing on Front Page
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function listing() {
        //pr($this->request->data);die;
        $this->layout = 'home';
        $uid = $this->Auth->user('id');
        $roleid = $this->Auth->user('role_id');
        $dataList = TableRegistry::get('BusinessLists');
        if (!empty($uid)) {
            $SaveSearchDatas = TableRegistry::get('SaveSearchDatas');

            $usreSaveSearchdata = $SaveSearchDatas->find('list');
            $userSaveSearchquery = $usreSaveSearchdata->where(['user_id' => $uid]);
            $userSaveSearch = $userSaveSearchquery->toArray();
            end($userSaveSearch);
            $lastDataKey = key($userSaveSearch);

            $userSearchdata = array();
            $userSearchdata[$lastDataKey + 1] = 'Select';
            foreach ($userSaveSearch as $key => $usd) {
                $userSearchdata[$key] = $usd;
                $lastkey = $key;
            }
            $userSearchdata[0] = "Other";
        }

        if ($roleid) {
            $roleid = $roleid;
        } else {
            $roleid = '';
        }
        if ($this->request->is(['post', 'put'])) {
            //pr($this->request->data);die(321);
            $params = array();
            if (!empty($this->request->data['sort'])) {
                $savesearch['order'] = $this->request->data['sort'];
                $params['order'] = $this->request->data['sort'];
            }
            if (!empty($this->request->data['state'])) {
                $params['state'] = base64_encode(implode(',', $this->request->data['state']));
            }
            if (!empty($this->request->data['city'])) {
                $params['city'] = base64_encode(implode(',', $this->request->data['city']));
            }
            if (!empty($this->request->data['bcategory'])) {
                $params['bcategory'] = base64_encode(implode($this->request->data['bcategory'], ','));
            }
            if (!empty($this->request->data['bsubcategory'])) {
                $params['bsubcategory'] = base64_encode(implode($this->request->data['bsubcategory'], ','));
            }
            if (!empty($this->request->data['min'])) {
                $params['min'] = $this->request->data['min'];
                $params['max'] = $this->request->data['max'];
            }
            if (!empty($this->request->data['addedFrom'])) {

                $params['addedFrom'] = base64_encode($this->request->data['addedFrom']);
            }
            if (!empty($this->request->data['addedTo'])) {

                $params['addedTo'] = base64_encode($this->request->data['addedTo']);
            }
            if (!empty($this->request->data['updatedFrom'])) {

                $params['updatedFrom'] = base64_encode($this->request->data['updatedFrom']);
            }
            if (!empty($this->request->data['seller'])) {

                $params['seller'] = base64_encode(implode($this->request->data['seller'], ','));
            }
            if (!empty($this->request->data['UpdatedTo'])) {
                $params['UpdatedTo'] = base64_encode($this->request->data['UpdatedTo']);
            }
            if (!empty($this->request->data['zip_code'])) {
                $params['zip'] = base64_encode($this->request->data['zip_code']);
            }
            if (!empty($this->request->data['Keyword'])) {
                $params['keyword'] = base64_encode($this->request->data['Keyword']);
            }
            if (!empty($this->request->data['radius'])) {
                $params['radius'] = base64_encode($this->request->data['radius']);
            }
            if (!empty($this->request->data['favorite'])) {
                $params['favorite'] = base64_encode($this->request->data['favorite']);
            }
            if (!empty($this->request->data['business_type'])) {
                $this->request->data['business_type'] = str_replace('1111', '0', $this->request->data['business_type']);
                $params['business_type'] = base64_encode($this->request->data['business_type']);
            }
            if (!empty($this->request->data['asking_min'])) {

                $params['asking_min'] = base64_encode($this->request->data['asking_min']);
            }
            if (!empty($this->request->data['asking_max'])) {

                $params['asking_max'] = base64_encode($this->request->data['asking_max']);
            }
            if (!empty($this->request->data['revenue_min'])) {

                $params['revenue_min'] = base64_encode($this->request->data['revenue_min']);
            }
            if (!empty($this->request->data['revenue_max'])) {

                $params['revenue_max'] = base64_encode($this->request->data['revenue_max']);
            }
            if (!empty($this->request->data['editda_min'])) {

                $params['editda_min'] = base64_encode($this->request->data['editda_min']);
            }
            if (!empty($this->request->data['editda_max'])) {

                $params['editda_max'] = base64_encode($this->request->data['editda_max']);
            }
            if (!empty($this->request->data['income_min'])) {

                $params['income_min'] = base64_encode($this->request->data['income_min']);
            }
            if (!empty($this->request->data['income_max'])) {

                $params['income_max'] = base64_encode($this->request->data['income_max']);
            }
            if (!empty($this->request->data['cash_min'])) {

                $params['cash_min'] = base64_encode($this->request->data['cash_min']);
            }
            if (!empty($this->request->data['cash_max'])) {

                $params['cash_max'] = base64_encode($this->request->data['cash_max']);
            }


            //$order['id'] = 'DESC';
            $order = array();
            return $this->redirect([
                        'controller' => 'business_lists', 'action' => 'listing',
                        '?' => $params
            ]);
        } else {
            //pr($this->request->query); 
            $filters = array();
            $order = array();
            $filters['status'] = ACTIVE;
            if (isset($this->request->query['state'])) {

                $bloc_state = base64_decode($this->request->query['state']);
                $filters['bloc_state IN'] = $bloc_state;
                $savesearch['state'] = $bloc_state;
            }
            if (isset($this->request->query['city'])) {

                $bloc_city = base64_decode($this->request->query['city']);
                $filters['bloc_city IN'] = $bloc_city;
                $savesearch['city'] = $bloc_city;
            }

            if (isset($this->request->query['bcategory'])) {
                $bcategory = base64_decode($this->request->query['bcategory']);
                $filters['bcategory IN'] = explode(',', $bcategory);
                $savesearch['cat'] = $bcategory;
            }
            if (isset($this->request->query['bsubcategory'])) {
                $bsubcategory = base64_decode($this->request->query['bsubcategory']);
                $filters['bsubcategory IN'] = explode(',', $bsubcategory);
                $savesearch['subcat'] = $bsubcategory;
            }
            if (isset($this->request->query['min'])) {
                $min = isset($this->request->query['min']) ? $this->request->query['min'] : '';
                $max = isset($this->request->query['max']) ? $this->request->query['max'] : '';

                $filters['fi_price AND'] = ['CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED) >=' => $min, 'CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED) <=' => $max];

                $savesearch['min'] = isset($this->request->query['min']) ? $this->request->query['min'] : '';
                $savesearch['max'] = isset($this->request->query['max']) ? $this->request->query['max'] : '';
            }
            if (isset($this->request->query['asking_min'])) {
                $a_min = isset($this->request->query['asking_min']) ? base64_decode($this->request->query['asking_min']) : '';
                $a_max = isset($this->request->query['asking_max']) ? base64_decode($this->request->query['asking_max']) : '';

                $filters['AND'][] = ['CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED) >=' => $a_min, 'CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED) <=' => $a_max];

                $savesearch['asking_min'] = isset($this->request->query['asking_min']) ? $this->request->query['asking_min'] : '';
                $savesearch['asking_max'] = isset($this->request->query['asking_max']) ? $this->request->query['asking_max'] : '';
            }

            if (isset($this->request->query['revenue_min'])) {
                $r_min = isset($this->request->query['revenue_min']) ? base64_decode($this->request->query['revenue_min']) : '';
                $r_max = isset($this->request->query['revenue_max']) ? base64_decode($this->request->query['revenue_max']) : '';

                $filters['AND'][] = ['CAST(REPLACE(BusinessLists.fi_revenue,",","") as SIGNED) >=' => $r_min, 'CAST(REPLACE(BusinessLists.fi_revenue,",","") as SIGNED) <=' => $r_max];

                $savesearch['revenue_min'] = isset($this->request->query['revenue_min']) ? $this->request->query['revenue_min'] : '';
                $savesearch['revenue_max'] = isset($this->request->query['revenue_max']) ? $this->request->query['revenue_max'] : '';
            }


            if (isset($this->request->query['editda_min'])) {
                $e_min = isset($this->request->query['editda_min']) ? base64_decode($this->request->query['editda_min']) : '';
                $e_max = isset($this->request->query['editda_max']) ? base64_decode($this->request->query['editda_max']) : '';

                $filters['AND'][] = ['CAST(REPLACE(BusinessLists.fi_ebitda,",","") as SIGNED) >=' => $e_min, 'CAST(REPLACE(BusinessLists.fi_ebitda,",","") as SIGNED) <=' => $e_max];

                $savesearch['editda_min'] = isset($this->request->query['editda_min']) ? $this->request->query['editda_min'] : '';
                $savesearch['editda_max'] = isset($this->request->query['editda_max']) ? $this->request->query['editda_max'] : '';
            }



            if (isset($this->request->query['income_min'])) {
                $i_min = isset($this->request->query['income_min']) ? base64_decode($this->request->query['income_min']) : '';
                $i_max = isset($this->request->query['income_max']) ? base64_decode($this->request->query['income_max']) : '';

                $filters['AND'][] = ['CAST(REPLACE(BusinessLists.fi_ebitda,",","") as SIGNED) >=' => $i_min, 'CAST(REPLACE(BusinessLists.fi_ebitda,",","") as SIGNED) <=' => $i_max];

                $savesearch['income_min'] = isset($this->request->query['income_min']) ? $this->request->query['income_min'] : '';
                $savesearch['income_max'] = isset($this->request->query['income_max']) ? $this->request->query['income_max'] : '';
            }



            if (isset($this->request->query['cash_min'])) {
                $c_min = isset($this->request->query['cash_min']) ? base64_decode($this->request->query['cash_min']) : '';
                $c_max = isset($this->request->query['cash_max']) ? base64_decode($this->request->query['cash_max']) : '';

                $filters['AND'][] = ['CAST(REPLACE(BusinessLists.fi_cashflow,",","") as SIGNED) >=' => $c_min, 'CAST(REPLACE(BusinessLists.fi_cashflow,",","") as SIGNED) <=' => $c_max];

                $savesearch['cash_min'] = isset($this->request->query['cash_min']) ? base64_decode($this->request->query['cash_min']) : '';
                $savesearch['cash_max'] = isset($this->request->query['cash_max']) ? base64_decode($this->request->query['cash_max']) : '';
            }


            if (isset($this->request->query['order'])) {
                $sort = $this->request->query['order'];
                $savesearch['order'] = $this->request->query['order'];
                if ($sort == 1) {
                    $order['id'] = 'DESC';
                } else if ($sort == 2) {
                    $order['CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED)'] = 'ASC';
                } else if ($sort == 3) {
                    $order['CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED)'] = 'DESC';
                } else if ($sort == 4) {
                    $order['CAST(REPLACE(BusinessLists.fi_cashflow,",","") as SIGNED)'] = 'ASC';
                } else if ($sort == 5) {
                    $order['CAST(REPLACE(BusinessLists.fi_cashflow,",","") as SIGNED)'] = 'DESC';
                } else {
                    $order['id'] = 'DESC';
                }
            } else {
                $order['id'] = 'DESC';
            }

            if (isset($this->request->query['zip'])) {
                $zip = base64_decode($this->request->query['zip']);
                $filters['bloc_zip IN'] = $zip;
                if (isset($this->request->query['radius'])) {
                    $radius = base64_decode($this->request->query['radius']);
                    $resulttt = $this->calculateRadius($zip, $radius);
                    $val = json_decode($resulttt);
                    $filters['bloc_zip IN'] = $val;
                    $savesearch['radius'] = $radius;
                }

                $savesearch['zip'] = $zip;
            }
            if (isset($this->request->query['business_type'])) {
                $business_type = base64_decode($this->request->query['business_type']);
                $filters['list_type IN'] = explode(',', $business_type);
                //$filters['list_type'] = $business_type;
                $savesearch['business_type'] = $business_type;
            }
            if (isset($this->request->query['keyword'])) {
                $Keyword = base64_decode($this->request->query['keyword']);
                $filters['OR'] = array('btitle LIKE' => '%' . $Keyword . '%', 'bddescription LIKE' => '%' . $Keyword . '%');
                //$filters['btitle LIKE'] = '%'.$Keyword.'%';
                //$filters['bddescription LIKE'] = '%'.$Keyword.'%';
                $savesearch['Keyword'] = $Keyword;
            }
            if (isset($this->request->query['favorite'])) {
                $favorite = base64_decode($this->request->query['favorite']);
                if ($favorite == 'yes' && !empty($uid)) {
                    $wishlists = TableRegistry::get('Wishlists');
                    $query = $wishlists->find('all');
                    $newdata = $query->select(['bussiness_list_id'])->where(['user_id' => $uid])->where(['status' => 2])->toArray();
                    if (!empty($newdata)) {
                        $bid = array();
                        $i = 0;
                        foreach ($newdata as $result) {
                            $bid[] = $result->bussiness_list_id;
                            $i++;
                        }
                        $filters['id IN'] = $bid;
                    }
                }
                $savesearch['favorite'] = $favorite;
            }
            if (isset($this->request->query['seller'])) {
                $seller = base64_decode($this->request->query['seller']);

                if ($seller == 'Broker') {
                    $userlists = TableRegistry::get('Users');
                    $query = $userlists->find('all');
                    $newdata = $query->select(['id'])->where(['role_id' => BROKER])->where(['status' => ACTIVE])->toArray();
                    if (!empty($newdata)) {
                        $user = array();
                        $i = 0;
                        foreach ($newdata as $result) {
                            $user[] = $result->id;
                            $i++;
                        }
                        $filters['user_id IN'] = $user;
                    }
                } elseif ($seller == 'FSBO') {
                    $userlists = TableRegistry::get('Users');
                    $query = $userlists->find('all');
                    $newdata = $query->select(['id'])->where(['role_id' => SELLER])->where(['status' => ACTIVE])->toArray();
                    if (!empty($newdata)) {
                        $user = array();
                        $i = 0;
                        foreach ($newdata as $result) {
                            $user[] = $result->id;
                            $i++;
                        }
                        $filters['user_id IN'] = $user;
                    }
                }
                $savesearch['seller'] = $seller;
            }


            if (isset($this->request->query['addedFrom']) && isset($this->request->query['addedTo'])) {
                $addedFrom = base64_decode($this->request->query['addedFrom']);
                $addedTo = base64_decode($this->request->query['addedTo']);
                $from = date('Y-m-d', strtotime($addedFrom));
                $to = date('Y-m-d', strtotime($addedTo));
                $filters[] = array("DATE_FORMAT(`created`, '%Y-%m-%d') between '$from' AND '$to' ");
                $savesearch['addedFrom'] = $addedFrom;
                $savesearch['addedTo'] = $addedTo;
            }
            if (isset($this->request->query['updatedFrom']) && isset($this->request->query['UpdatedTo'])) {
                $updatedFrom = base64_decode($this->request->query['updatedFrom']);
                $UpdatedTo = base64_decode($this->request->query['UpdatedTo']);
                $from = date('Y-m-d', strtotime($updatedFrom));
                $to = date('Y-m-d', strtotime($UpdatedTo));
                $filters[] = array("DATE_FORMAT(`modified`, '%Y-%m-%d') between '$from' AND '$to' ");
                $savesearch['updatedFrom'] = $updatedFrom;
                $savesearch['UpdatedTo'] = $UpdatedTo;
            }
            //pr($filters);
            $limit = isset($_COOKIE['businessPerPageHome']) ? $_COOKIE['businessPerPageHome'] : 12;
            try {

                $data = $this->Paginator->paginate($dataList, [
                    'limit' => $limit,
                    'conditions' => [$filters],
                    'sortWhitelist' => [
                        'id', 'CAST(REPLACE(BusinessLists.fi_price,",","") as SIGNED)',
                        'CAST(REPLACE(BusinessLists.fi_cashflow,",","") as SIGNED)'
                    ],
                    'contain' => ['BusinessGallaries'],
                    'order' => $order
                ]);
                //echo count($data); die;
            } catch (NotFoundException $e) {

                $page = $this->request->params['paging']['BusinessLists']['page'];
                if ($page >= 1) {
                    return $this->redirect(array("page" => $page - 1));
                } else {
                    $data = array();
                    return $this->redirect(array("page" => $page));
                }
            }
        }
        $businesscategories = TableRegistry::get('BusinessCategories');
        $query1 = $businesscategories->query();
        $query = $query1->find('all')->contain(['BusinessSubcategories'])->where(['status' => ACTIVE])->order(['BusinessCategories.name' => 'ASC']);
        $catlist = $query->all()->toArray();
        $state = array();
        $city = array();
        $country = json_decode($this->country());
        $state = json_decode($this->state(223), true);

        $city = json_decode($this->city());
        $this->set(compact('data', 'roleid', 'glrylist', 'catlist', 'asklist', 'state', 'city', 'savesearch', 'userSearchdata', 'allnumber'));
    }

    /**
     * @Business Document upload
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function uploadfilestTemp() {
        $string = $this->random_string(10);
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        $output_dir = WWW_ROOT . 'img' . DS . 'admin_doc' . DS;
        if (isset($_FILES["myfile"])) {
            $ret = array();
            $error = $_FILES["myfile"]["error"];
            if (!is_array($_FILES["myfile"]["name"])) { //single file
                $fileName = str_replace(' ', '_', $_FILES["myfile"]["name"]);
                move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $userId . $string . $fileName);
                //$ret['file_name'] = $fileName;
                //$ret['title'] = $fileName;
                $ret[] = $userId . $string . $fileName;
            } else {  //Multiple files, file[]
                $fileCount = count($_FILES["myfile"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = str_replace(' ', '_', $_FILES["myfile"]["name"][$i]);
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $userId . $string . $fileName);
                    //$ret['file_name'] = $fileName;
                    //$ret['title'] = $fileName;
                    $ret[] = $userId . $string . $fileName;
                }
            }
            echo json_encode($ret);
        }
        exit;
    }

    /**
     * @Business Document upload
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function uploadimageTemp() {

        $string = $this->random_string(10);
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        $output_dir = WWW_ROOT . 'img' . DS . 'user_business_gallary' . DS . $this->request->data['docs_folder'] . DS;
	$ret = array();

        if (!file_exists($output_dir)) {
            mkdir($output_dir, 0777, true);
        }

        if (isset($_FILES["featured_image"]) && $_FILES["featured_image"]['error'] == 0) {
		
            $fileName = str_replace(" ", "", strtolower($_FILES["featured_image"]["name"]));
            $newName = 'featured-' . $userId . '-' . $this->request->data['docs_folder'] . '-' . $fileName;
            //$this->imagethumbnill($_FILES["featured_image"]['tmp_name'],$_FILES["featured_image"]["name"],$newName);
            move_uploaded_file($_FILES["featured_image"]["tmp_name"], $output_dir . $newName);
            $ret['name'] = $newName;
            $ret['time'] = date("m/d/y", filemtime($output_dir . $newName));
        }
	if (isset($_FILES["listing_image"]) && $_FILES["listing_image"]['error'] == 0) {
		
            $fileName = str_replace(" ", "", strtolower($_FILES["listing_image"]["name"]));
            $newName = 'listing-' . $userId . '-' . $this->request->data['docs_folder'] . '-' . $fileName;
            //$this->imagethumbnill($_FILES["listing_image"]['tmp_name'],$_FILES["listing_image"]["name"],$newName);
            move_uploaded_file($_FILES["listing_image"]["tmp_name"], $output_dir . $newName);
            $ret['name'] = $newName;
            $ret['time'] = date("m/d/y", filemtime($output_dir . $newName));
        }
	if (isset($_FILES["myfile"]) && $_FILES["myfile"]['error'] == 0) {
		
            $fileName = str_replace(" ", "", strtolower($_FILES["myfile"]["name"]));
            $newName = 'files-' . $userId . '-' . $this->request->data['docs_folder'] . '-' . $fileName;
            //$this->imagethumbnill($_FILES["myfile"]['tmp_name'],$_FILES["myfile"]["name"],$newName);
            move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $newName);
            $ret['name'] = $newName;
            $ret['time'] = date("m/d/y", filemtime($output_dir . $newName));
        }

        if (isset($_FILES["myimage"])) {
            $error = $_FILES["myimage"]["error"];
            if (!is_array($_FILES["myimage"]["name"])) { //single file

                $fileName = $_FILES["myimage"]["name"];
                $newname = $userId . $string . $fileName;
                $this->imagethumbnill($_FILES["myimage"]['tmp_name'], $fileName, $newname);
                $md = move_uploaded_file($_FILES["myimage"]["tmp_name"], $output_dir . $userId . $string . $fileName);



                //$ret['name']= $this->Html->link($userId.$string.$fileName,'img/user_business_gallary/'.$userId.$string.$fileName);
                $ret['name'] = $userId . $string . $fileName;
                $ret['time'] = date("m/d/y", filemtime($output_dir . $userId . $string . $fileName));
            } else {  //Multiple files, file[]
                $fileCount = count($_FILES["myimage"]["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                    $fileName = $_FILES["myimage"]["name"][$i];
                    move_uploaded_file($_FILES["myimage"]["tmp_name"][$i], $output_dir . $userId . $string . $fileName);
                    $ret['name'] = $userId . $string . $fileName;
                    $ret['time'] = date("m/d/y", filemtime($output_dir . $userId . $string . $fileName));
                }
            }
        }
        echo json_encode($ret);
        exit;
    }

    public function dropfile($filename,$id=null) {
        
	$f = explode("-",$filename);
	$id = base64_decode($id);
	$output_dir = WWW_ROOT . 'img' . DS . 'user_business_gallary' . DS . $f[2] . DS;
	$this->autoRender = false;

	if(unlink($output_dir.$filename))
	{
            //rmdir($output_dir);
	    if($id)
	    {
		if($f[0]=='files')
		{
		    $BusinesListsTable = TableRegistry::get('BusinessDocuments');
		    $blist = $BusinesListsTable->get($id);
		}
		else
		{
		    $BusinesListsTable = TableRegistry::get('BusinessGallaries');
		    $blist = $BusinesListsTable->get($id);
		}
		
		if($BusinesListsTable->delete($blist))
		{
		    echo 1;
		}
		else
		{
		    echo '12' ; 
		}		
	    }
	    else
	    {
		echo 1; 
	    }
	   // echo 1;
	//die; 
	}
	else
	{
	    echo 0;
	}
    //die;

    }

    function imagethumbnill($image, $fileName, $newname) {


        //require_once(ROOT . DS  . 'vendor' . DS  . 'imageresize' . DS . 'ImageResize.php');
        //$filename = $image;
        //$image = new \Eventviva\ImageResize($filename); 
        //$image->resize(400, 216);
        ////$image->resizeToHeight(216);
        ////$image->resizeToWidth(400);
        //$image->save($output_dir);

        $output_dir = WWW_ROOT . 'img' . DS . 'business_thumbnail' . DS . $newname;
        list($width, $height) = getimagesize($image);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if ($extension == "jpg" || $extension == "jpeg") {
            $src = imagecreatefromjpeg($image);
        } else if ($extension == "png") {
            $src = imagecreatefrompng($image);
        } else {
            $src = imagecreatefromgif($image);
        }

        $newwidth = 400;
        $newheight = 216;
        $tmp = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        imagejpeg($tmp, $output_dir, 100);
    }

    /**
     * @Unlink Business Document upload
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deletedocument() {
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        $output_dir = WWW_ROOT . 'img' . DS . 'admin_doc' . DS;
        if (isset($_POST["op"]) && $_POST["op"] == "delete" || isset($_POST['name'])) {
            $fileName = $_POST['name'];

            $fileName = str_replace("..", ".", $fileName); //required. if somebody is trying parent folder files	
            $filePath = $output_dir . $fileName;
            $BusinessDocumentsTable = TableRegistry::get('BusinessDocuments');
            $docquery = $BusinessDocumentsTable->find('all');
            $docdata = $docquery->where(['url' => $fileName]);
            if ($docdata->toArray()) {
                $query = $BusinessDocumentsTable->query();
                $query->delete()
                        ->where(['url' => $fileName])
                        ->execute();
            }





            if (file_exists($filePath)) {
                unlink($filePath);

                //$BusinessDocumentsTable =  TableRegistry::get('BusinessDocuments');
                //$query = $BusinessDocumentsTable->query();
                //	$query->delete()
                //	->where(['url' => $fileName])
                //	->execute();
            }
            echo "Deleted File " . $fileName . "<br>";
        }
        exit;
    }

    /**
     * @Unlink Business Image upload
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deleteimage() {
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        $output_dir = WWW_ROOT . 'img' . DS . 'user_business_gallary' . DS;
        if (isset($_POST["op"]) && $_POST["op"] == "delete" || isset($_POST['name'])) {
            $fileName = $_POST['name'];

            $fileName = str_replace("..", ".", $fileName); //required. if somebody is trying parent folder files	
            $filePath = $output_dir . $fileName;
            $BusinessImageTable = TableRegistry::get('BusinessGallaries');
            $docquery = $BusinessImageTable->find('all');
            $docdata = $docquery->where(['url' => $fileName]);

            if ($docdata->toArray()) {
                $query = $BusinessImageTable->query();
                $query->delete()
                        ->where(['url' => $fileName])
                        ->execute();
            }

            if (file_exists($filePath)) {
                unlink($filePath);

                //$BusinessDocumentsTable =  TableRegistry::get('BusinessDocuments');
                //$query = $BusinessDocumentsTable->query();
                //	$query->delete()
                //	->where(['url' => $fileName])
                //	->execute();
            }
            echo "Deleted File " . $fileName . "<br>";
        }
        exit;
    }

    /**
     * @Unlink Business Image upload
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deleteimageTemp() {
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        $output_dir = WWW_ROOT . 'img' . DS . 'user_business_gallary' . DS;
        if (isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name'])) {
            $fileName = $_POST['name'];
            $fileName = str_replace("..", ".", $fileName); //required. if somebody is trying parent folder files	
            $filePath = $output_dir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            echo "Deleted File " . $fileName . "<br>";
        }
        exit;
    }

    /**
     * @Unlink Business Document upload
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deletefilestTemp() {
        $userId = $this->Auth->user('id');
        $roleId = $this->Auth->user('role_id');
        $adminRole = array(0 => '', 1 => 'avatar', 2 => 'seller', 3 => 'buyer', 4 => 'broker', 5 => 'advisor');
        $output_dir = WWW_ROOT . 'img' . DS . 'admin_doc' . DS;
        if (isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name'])) {
            $fileName = $_POST['name'];
            $fileName = str_replace("..", ".", $fileName); //required. if somebody is trying parent folder files	
            $filePath = $output_dir . $fileName;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            echo "Deleted File " . $fileName . "<br>";
        }
        exit;
    }

    /**
     * @ajaxlogin
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function ajaxlogin() {
        $this->autoRender = false;
        $usersTable = TableRegistry::get('Users');
        $user = $usersTable->newEntity();
        $session = $this->request->session();
        if ($this->request->is('post')) {
            $response = [];

            $user = $this->Auth->identify();
	  //  pr($user);die('here');
            if(!empty($user))
	    {
	    $session->write('temp.userData', $user);
	    $data = $usersTable->get($user['id'], ['contain' => ['UserDetails']]);
            if ($user['account_security'] == ACTIVE && $user['veriy_phone'] == ACTIVE && !empty($data['user_detail']['contact_no'])) {
                $response['status'] = 2;
                $response['id'] = $user['id'];
                $response['contact_no'] = $data['user_detail']['contact_no'];
                $contactNoLen = (strlen($data['user_detail']['contact_no'])) - 4;
                $satr = '';
                for ($i = 0; $i < $contactNoLen; $i++) {
                    $satr .= "*";
                }
                $response['show_number'] = $satr . substr($data['user_detail']['contact_no'], $contactNoLen, 4);
            } else {
                if (isset($user['status']) && $user['status'] == ACTIVE) {
                    if ($user['role_id'] == ADMIN || $user['role_id'] == BUYER || $user['role_id'] == BROKER || $user['role_id'] == SELLER || $user['role_id'] == ADVISOR || $user['role_id'] == MANAGER) {
                        $this->Auth->setUser($user);
                        $dataList = TableRegistry::get('Users');
                        $AdminId = $this->Auth->user('id');
                        $users = $dataList->get($AdminId);
                        $session->write('Config.data', $users);
                        $response['status'] = 1;
                    } else {
                        $response['status'] = 3;
                        $response['msg'] = "Invalid username or password, try again.";
                    }
                } else if (isset($user['status']) && $user['status'] != ACTIVE) {
                    $response['status'] = 3;
                    $response['msg'] = "Please verify your account.";
                } else {
                    $response['status'] = 3;
                    $response['msg'] = "Invalid username or password, try again.";
                }
            }
	    }
	    else{
		$response['status'] = 3;
                        $response['msg'] = "Invalid username or password, try again.";
	    }
            echo json_encode($response);
        }
        exit;
    }

    /**
     * @ajaxloginstep1
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function ajaxloginstep1() {
        $this->autoRender = false;
        $userTable = TableRegistry::get('Users');
        $UserDetails = TableRegistry::get('UserDetails');

        if ($this->request->is(['post', 'put'])) {
            $userID = $this->request->data['user_id'];
            $contact_no = $this->request->data['contact_no'];
            $data = $userTable->get($userID, ['contain' => ['UserDetails']]);
            $result = $userTable->find('all')->contain(['UserDetails'])->where(['UserDetails.contact_no' => $contact_no])->where(['Users.id' => $userID, 'Users.status' => ACTIVE])->toArray();
            if (!empty($result[0])) {
                $mobileNo = $contact_no;
                $OTPmessage = $this->generateRandomString(); //$result[0]->security_code;
                $msg = parent::sendSms($mobileNo, $OTPmessage);

                $query = $userTable->query();
                $query->update()
                        ->set(['security_code' => $OTPmessage
                        ])
                        ->where(['id' => $userID])
                        ->execute();
                $response['status'] = 1;
                $response['msg'] = "Please check your cell for OTP .";
            } else {
                $response['status'] = 2;
                $response['msg'] = "Please check your cell number .";
            }
            echo json_encode($response);
        }
        exit;
    }

    /**
     * @ajaxloginstep2
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function ajaxloginstep2() {
        $this->autoRender = false;


        $session = $this->request->session();
        $userTable = TableRegistry::get('Users');
        $UserDetails = TableRegistry::get('UserDetails');
        $session = $this->request->session();

        if ($this->request->is(['post', 'put'])) {
            $userID = $this->request->data['user_id'];
            $data = $userTable->get($userID, ['contain' => ['UserDetails']]);
            $security_codee = $this->request->data['SecurityCode'];
            $result = $userTable->find('all')->where(['Users.id' => $userID, 'Users.status' => ACTIVE, 'security_code' => $security_codee])->toArray();
            $setdata = $session->read('temp.userData');
            //pr($setdata);die;

            if (!empty($result[0])) {
                if (isset($result[0]->status) && $result[0]->status == ACTIVE) {
                    if ($result[0]->role_id == ADMIN || $result[0]->role_id == BUYER || $result[0]->role_id == BROKER || $result[0]->role_id == SELLER || $result[0]->role_id == ADVISOR || $result[0]->role_id == MANAGER) {
                        $this->Auth->setUser($setdata);
                        $dataList = TableRegistry::get('Users');
                        $AdminId = $userID;

                        $users = $dataList->get($AdminId, ['contain' => ['UserDetails']]);
                        $session->write('Config.data', $users);
                        $response['status'] = 1;
                    } else {
                        $response['status'] = 0;
                        $response['msg'] = "Invalid username or password, try again.";
                    }
                }
            } else {

                $response['status'] = 2;
                $response['msg'] = "Invaid OTP please try again.";
            }
            echo json_encode($response);
        }
        exit;
    }

    public function ajaxregister() {
        $this->autoRender = false;
        if ($this->request->is(['post', 'put'])) {
            $usersTable = TableRegistry::get('Users');
            $token = $this->request->data['token'] = bin2hex(openssl_random_pseudo_bytes(16));
            $userDetails = TableRegistry::get('UserDetails');
            $user = $usersTable->newEntity();
            $userDetail = $userDetails->newEntity();

            $usersTable->patchEntity($user, $this->request->data, ['validate' => 'default']);
            $errorInputs = [];
            $responce = [];
            if (!$user->errors()) {
                $user->role_id = BUYER;
                $user->status = INACTIVE;
                $user->reference = 0;
                $user->invitation_id = 0;
                if ($result = $usersTable->save($user)) {
                    $userDetail->user_id = $result->id;
                    $userDetail->country = 223;
                    $userDetail->state = 3421;
                    $userDetail->city = 135749;
                    $userDetails->save($userDetail);

                    $lastId = $result->id;
                    $username = $this->request->data['first_name'];
                    $password = $this->request->data['password'];
                    $userEmail = $this->request->data['email'];
                    $activation_link = SITE_URL . 'users/activateAccount/' . $token . '/' . base64_encode($lastId);
                    $EmailTemplates = TableRegistry::get('EmailTemplates');
                    $query = $EmailTemplates->find('all')
                            ->where(['EmailTemplates.slug' => 'user_registration'])
                            ->where(['EmailTemplates.status' => ACTIVE]);

                    $template = $query->first();
                    if ($template) {
                        $mailMessage = str_replace(array('{{username}}', '{{activation_link}}', '{{email}}', '{{password}}'), array($username, $activation_link, $userEmail, $password), $template->description);
                        $to = $userEmail;
                        $subject = $template->subject;
                        $message = $mailMessage;

                        $EmailTemplates->delivery = 'debug';
                        //pr(parent::sendMail($to, $subject, $message));die;
                        if (parent::sendMail($to, $subject, $message)) {
                            $this->Flash->success(__('Please check your email for activate account'));
                        }
                        $responce['status'] = 1;
                        $responce['msg'] = 'Thank you for registering with us, your application is under review. Soon you will receive an email with an account activation link.';
                    } else {
                        $responce['status'] = 1;
                        $responce['msg'] = 'Thank you for registering with us, your application is under review. Soon you will receive an email with an account activation link.';
                    }
                }
            } else {
                foreach ($user->errors() as $key => $value) {
                    $messageerror = [];
                    foreach ($value as $key2 => $value2) {
                        $messageerror[] = $value2;
                    }
                    $errorInputs[$key] = implode(",", $messageerror);
                }
                $responce['status'] = 0;
                $responce['msg'] = $errorInputs;
                //$this->Flash->error($this->errorMessage($user->errors()),['params'=>['class' => 'alert alert-danger']]);
            }
        }
        echo json_encode($responce);
        exit;
    }

    /**
     * @update wishlist
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function wishlist() {
        $this->autoRender = false;
        $wishlists = TableRegistry::get('Wishlists');
        if ($this->request->is(['post', 'put'])) {
            $uid = $this->request->data['uid'];
            $bid = $this->request->data['bid'];
            $query = $wishlists->find('all');
            $newdata = $query->where(['user_id' => $uid])->where(['bussiness_list_id' => $bid]);
            $data = $newdata->toArray();
            if (empty($data)) {
                $wishlistsEntity = $wishlists->newEntity();
                $wishlistsEntity->user_id = $uid;
                $wishlistsEntity->bussiness_list_id = $bid;
                $wishlistsEntity->status = ACTIVE;
                $wishlistsEntity->created = date("Y-m-d h:i:s");
                $wishlistsEntity->modified = date("Y-m-d h:i:s");
                $wishlists->save($wishlistsEntity);
                echo ACTIVE;
            } else {
                $newstatus = array(INACTIVE => ACTIVE, ACTIVE => INACTIVE);
                $wishdata = $data;
                $wishid = $wishdata[0]->id;
                $status = $wishdata[0]->status;
                $data = $wishlists->get($wishid);
                $data->status = $newstatus[$status];
                $data->modified = date("Y-m-d h:i:s");
                $wishlists->save($data);
                echo $newstatus[$status];
            }
            exit;
        }
    }

    /**
     * @admin Update Wishlist
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function adminUpdateWishlist() {
        $this->autoRender = false;

        $wishlists = TableRegistry::get('Wishlists');
        $wishlistid = $this->request->data['wishlistid'];
        $data = $wishlists->get($wishlistid);
        $data->status = INACTIVE;
        $data->modified = date("Y-m-d h:i:s");
        $wishlists->save($data);
        exit;
    }

    /**
     * @Contact Seller
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function contact() {


        $this->autoRender = false;
        $contactSellerList = TableRegistry::get('ContactSellers');
        $BussinessList = TableRegistry::get('BusinessLists');
        $NotificationList = TableRegistry::get('NotificationSettings');
        $userTable = TableRegistry::get('Users');
        if ($this->request->is(['post', 'put'])) {

            $bid = $this->request->data['bid'];

            $data = $BussinessList->get($bid);

            if (!empty($data) && $data->user_id) {
                $reciverId = $data->user_id;
                $users = $userTable->get($reciverId, ['contain' => ['UserDetails']]);
                $email = $users->email;
                $username = $users->first_name;
            } else {
                echo 'Some Error Occured, Please try later';
                exit();
            }

            $senderId = $this->request->data['user_id'];
            $message = $this->request->data['message'];
            $contactEntity = $contactSellerList->newEntity();
            $contactEntity->sender_id = $senderId;
            $contactEntity->reciver_id = $reciverId;
            $contactEntity->bussiness_list_id = $bid;
            $contactEntity->email = $email;
            $contactEntity->message = $message;
            $contactEntity->status = INACTIVE;
            $contactEntity->read_status = 0;

            $contactEntity->created = date("Y-m-d h:i:s");
            $contactEntity->modified = date("Y-m-d h:i:s");

            if ($contactSellerList->save($contactEntity)) {

                $notificationQuery = $NotificationList->find('all')->where(['user_id' => $reciverId, 'type_alert' => BUYERALERT, 'alert' => 1])->toArray();

                if (!empty($notificationQuery)) {

                    $activation_link = SITE_URL . 'business_lists/editBusiness/' . base64_encode($bid);
                    $EmailTemplates = TableRegistry::get('EmailTemplates');
                    $query = $EmailTemplates->find('all')
                            ->where(['EmailTemplates.slug' => 'contact_seller']);
                    $template = $query->first();
                    if ($template) {
                        $mailMessage = str_replace(array('{{username}}', '{{activation_link}}'), array($username, $activation_link), $template->description);
                        $to = $email;
                        $subject = $template->subject;
                        $message = $mailMessage;
                        parent::sendMail($to, $subject, $message);
                    }
                }
                //if($senderId){
                //	$users = $userTable->get($senderId);
                //	$senderemail = $users->email;
                //	$username = $users->first_name;
                //	$activation_link = SITE_URL.'business_lists/editBusiness/'.base64_encode($bid);
                //	$EmailTemplates= TableRegistry::get('EmailTemplates');
                //	$query = $EmailTemplates->find('all')
                //		->where(['EmailTemplates.slug' => 'contact_seller']);
                //	$template = $query->first();
                //	if($template){
                //		$mailMessage = str_replace(array('{{username}}', '{{activation_link}}'),
                //					   array($username,$activation_link),$template->description);
                //		$to = $senderemail;
                //		$subject = $template->subject;
                //		$message = $mailMessage;
                //		parent::sendMail($to, $subject, $message); 
                //	}
                //
				//
				//}
            }

            echo 'message sent successfully';
        }

        exit;
    }

    /**
     * @view business WishList
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function businessWishList() {
        $this->layout = 'admin';
        $Wishlists = TableRegistry::get('Wishlists');
        $AdminId = $this->Auth->user('id');
        try {
            $data = $this->Paginator->paginate($Wishlists, [
                'limit' => Configure::read('pageRecord'),
                'conditions' => [
                    'Wishlists.status' => ACTIVE,
                    'Wishlists.user_id' => $AdminId,
                ],
                'contain' => ['Businesslists', 'BusinessGallaries'],
                'order' => ['Wishlists.id' => 'desc'],
		'group'=>'Wishlists.bussiness_list_id'
            ]);
        } catch (NotFoundException $e) {
            $page = $this->request->params['paging']['Wishlists']['page'];
            if ($page >= 1) {
                return $this->redirect(array("page" => $page - 1));
            } else {
                $data = array();
                return $this->redirect(array("page" => $page - 1));
            }
        }
        $this->set('data', $data->toArray());
    }

    /**
     * @view business Contact SELLER
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function businessContactSellerList() {
        $this->layout = 'admin';
        $contactSellerList = TableRegistry::get('ContactSellers');
        $AdminId = $this->Auth->user('id');
        try {
            $data = $this->Paginator->paginate($contactSellerList, [
                'limit' => Configure::read('pageRecord'),
                'conditions' => [
                    //'ContactSellers.status !=' => DELETED,
                    //'ContactSellers.is_deleted !=' => 1,
                    'ContactSellers.reciver_id' => $AdminId,
                    'ContactSellers.status' => 1,
                ],
                'order' => ['ContactSellers.status' => 'ASC','ContactSellers.id' => 'DESC']
            ]);
        } catch (NotFoundException $e) {
            $page = $this->request->params['paging']['ContactSellers']['page'];
            if ($page >= 1) {
                return $this->redirect(array("page" => $page - 1));
            } else {
                $data = array();
                return $this->redirect(array("page" => $page - 1));
            }
        }

        $this->set('data', $data->toArray());
    }

    /**
     * @admin Update Contact Seller
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateContactSeller() {
        $this->autoRender = false;
        //pr($this->request->data);die;
        $contactSellerList 	= TableRegistry::get('ContactSellers');
        $exchangeSpacesList 	= TableRegistry::get('ExchangeSpaces');
        $EmailTemplates 	= TableRegistry::get('EmailTemplates');
        $NotificationList 	= TableRegistry::get('NotificationSettings');
	$userTable 		= TableRegistry::get('Users');

        $id 		= $this->request->data['tableID'];
        $currentstat 	= $this->request->data['currentStatus'];
        $bid 		= $this->request->data['bid'];
        $sender 	= $this->request->data['senderId'];
        $textmessage 	= $this->request->data['message'];
	$reason 	= $this->request->data['reason'];
        $reasons = ['reason1','reason2','reason3','reason4'];
        $users 		= $userTable->get($sender, ['contain' => ['UserDetails']]);
        $emailId 	= $users->email;
        $username 	= $users->first_name;
       
        $userID 	= $this->request->session()->read('Auth.User.id');

        if ($currentstat == ACCEPTED) {

            $cdata = $contactSellerList->get($id);
            $cdata->is_deleted = 1;
            $cdata->modified = date("Y-m-d h:i:s");
            $contactSellerList->save($cdata);



            $activation_link = SITE_URL . 'business_lists/listing/';

            $query = $EmailTemplates->find('all')
                    ->where(['EmailTemplates.slug' => 'contact_seller_accept']);
            $template = $query->first();
            if ($template) {
		if($emailId){
		    $mailMessage = $textmessage;
		    $to = $emailId;
		    $subject = $template->subject;
		    $message = "Your inquiry Accepted";
		    parent::sendMail($to, $subject, $message);
		}
            }
	    $data 	= $contactSellerList->get($id);
            $data->status = ACCEPTED;
	    $data->modified = date("Y-m-d h:i:s");
	    $contactSellerList->save($data);
            //echo $bid;die;
            $exchangeSpacesEntity = $exchangeSpacesList->newEntity();
            $exchangeSpacesEntity->bussiness_list_id = $bid;
            $exchangeSpacesEntity->sender_id = $sender;
            $exchangeSpacesEntity->recevier_id = $userID;
            $exchangeSpacesEntity->f_history = LOCKED;
            $exchangeSpacesEntity->f_summary = LOCKED;
            $exchangeSpacesEntity->f_plan = LOCKED;
            $exchangeSpacesEntity->status = PRENDA;

            $exchangeSpacesEntity->created = date('Y-m-d h:i:s');
            $exchangeSpacesEntity->modified = date('Y-m-d h:i:s');
            $exchangeSpacesList->save($exchangeSpacesEntity);

            //echo "2";
        } else if ($currentstat == REJECTED) {
	    
            $activation_link = SITE_URL . 'business_lists/listing/';
            $EmailTemplates = TableRegistry::get('EmailTemplates');
            $query = $EmailTemplates->find('all')
                    ->where(['EmailTemplates.slug' => 'contact_seller_reject']);
            $template = $query->first();
            if ($template) {
                 $to = $emailId;
                $subject = $template->subject;
                $message = str_replace(['{{username}}','{{reason}}','{{massage}}'],[$username,$reasons[$reason],$textmessage],$template->description);
                parent::sendMail($to, $subject, $message);
            }
	    $query1 = $contactSellerList->query();
            $query1->delete()->where(['id' => $id])->execute();
            //$data->status = REJECTED;
            //$data->reason = $this->request->data['reason'];
            //echo "3";
        }


        
        return $this->redirect(array('controller' => 'business_lists', 'action' => 'businessContactSellerList'));
    }

    /**
     * @View Contact Seller Message
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function viewMessage($id = null) {
        $this->layout = 'admin';
        $contactSellerList = TableRegistry::get('ContactSellers');
        $pageId = base64_decode($id);
        $data = $contactSellerList->get($pageId);
        $query = $contactSellerList->query();
        $query->update()
                ->set(['read_status' => 1])
                ->where(['id' => $pageId])
                ->execute();
        $this->set('data', $data);
    }

    /**
     * @update Search data
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function saveSearchAjax() {

        $this->autoRender = false;
        $saveSearchDatas = TableRegistry::get('SaveSearchDatas');

        $catData = $this->request->data['catData'];
        $subCatData = $this->request->data['subCatData'];
        $locData = $this->request->data['locData'];
        $min = $this->request->data['min'];
        $max = $this->request->data['max'];
        $sort = $this->request->data['sort'];
        $userId = $this->request->data['userId'];
        $title = $this->request->data['title'];
        $savesearchlist = $this->request->data['savesearchlist'];

        if ($savesearchlist != 0) {
            $savesearchEntity = $saveSearchDatas->get($savesearchlist);
            $savesearchEntity->bcategory = isset($catData) ? $catData : '';
            $savesearchEntity->bsubcategory = isset($subCatData) ? $subCatData : '';
            $savesearchEntity->bloc_state = isset($locData) ? $locData : '';
            $savesearchEntity->min_price = isset($min) ? $min : '';
            $savesearchEntity->max_price = isset($max) ? $max : '';
            $savesearchEntity->short = isset($sort) ? $sort : '';
            $savesearchEntity->user_id = $userId;
            $savesearchEntity->created = date("Y-m-d h:i:s");
            $savesearchEntity->modified = date("Y-m-d h:i:s");
            $saveSearchDatas->save($savesearchEntity);
        } else {
            $savesearchEntity = $saveSearchDatas->newEntity();
            $savesearchEntity->bcategory = isset($catData) ? $catData : '';
            $savesearchEntity->bsubcategory = isset($subCatData) ? $subCatData : '';
            $savesearchEntity->bloc_state = isset($locData) ? $locData : '';
            $savesearchEntity->min_price = isset($min) ? $min : '';
            $savesearchEntity->max_price = isset($max) ? $max : '';
            $savesearchEntity->short = isset($sort) ? $sort : '';
            $savesearchEntity->title = $title;
            $savesearchEntity->user_id = $userId;
            $savesearchEntity->created = date("Y-m-d h:i:s");
            $savesearchEntity->modified = date("Y-m-d h:i:s");
            $saveSearchDatas->save($savesearchEntity);
        }

        echo "Save search successfully";
        exit;
    }

    /**
     * @Download Business Image's and Business Document
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function updateFiInformation() {
        $this->autoRender = false;
        $bussinessLists = TableRegistry::get('BussinessLists');
        if ($this->request->is(['post', 'put'])) {
            $bid = base64_decode($this->request->data['bussiness_list_id']);
            $fiLatestYear = $this->request->data['fi_latest_year'];
            $fiLatestQuarter = $this->request->data['fi_latest_quarter'];
            // $fiQuarterlyAnnual 		= $this->request->data['fi_quarterly_annual'];

            $bussinessListsEntity = $bussinessLists->get($bid);
            $bussinessListsEntity->fi_latest_year = $fiLatestYear;
            $bussinessListsEntity->fi_latest_quarter = $fiLatestQuarter;
            // $bussinessListsEntity->fi_quarterly_annual 		= $fiQuarterlyAnnual;
            $bussinessLists->save($bussinessListsEntity);
        }
        exit;
    }

    /**
     * @Download Business Image's and Business Document
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function downloadFile($type = null, $file = null) {
        ignore_user_abort(true);
        set_time_limit(0);
        if ($type == "imgtype") {
            $path = WWW_ROOT . 'img' . DS . 'user_business_gallary' . DS;
        } else {
            $path = WWW_ROOT . 'img' . DS . 'admin_doc' . DS;
        }

        $dl_file = preg_replace("([^\w\s\d\-_~,;:\[\]\(\].]|[\.]{2,})", '', $file); // simple file name validation
        $dl_file = filter_var($dl_file, FILTER_SANITIZE_URL); // Remove (more) invalid characters
        $fullPath = $path . $dl_file;
        if ($fd = fopen($fullPath, "r")) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);
            switch ($ext) {
                case "pdf":
                    header("Content-type: application/pdf");
                    header("Content-Disposition: attachment; filename=\"" . $path_parts["basename"] . "\""); // use 'attachment' to force a file download
                    break;
                // add more headers for other content types here
                default;
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: filename=\"" . $path_parts["basename"] . "\"");
                    break;
            }
            header("Content-length: $fsize");
            header("Cache-control: private"); //use this to open files directly
            while (!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose($fd);
        exit;
    }

   /**
     * @BusinessPlaneAll
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function BusinessPlaneAll() {
        $this->autoRender = false;
        //pr($this->request->data);die;
        $BusinessPlans = TableRegistry::get('BusinessPlans');
        $userId = $this->Auth->user('id');

        if ($this->request->is(['post', 'put'])) {

            $field_name = $this->request->data['field_name'];
            $section = $this->request->data['section'];
            $previous_year_data = $this->request->data['previous_year_data'];
            $current_year_data = $this->request->data['current_year_data'];
            $year_one = $this->request->data['year_one'];
            $year_two = $this->request->data['year_two'];
            $year_three = $this->request->data['year_three'];
            $year_four = $this->request->data['year_four'];
            $year_five = $this->request->data['year_five'];

            $current_year = $this->request->data['current_year'];
            $tab = $this->request->data['tab'];
            $bussiness_list_id = base64_decode($this->request->data['bussiness_list_id']);

            $query = $BusinessPlans->query();
            $query->delete()->where(['tab' => $tab])->where(['user_id' => $userId])->where(['bussiness_list_id' => $bussiness_list_id])->execute();


            $countfield_name = count($field_name);
            for ($j = 0; $j < $countfield_name; $j++) {
                $sourceEntity = $BusinessPlans->newEntity();
                $sourceEntity->user_id = $userId;
                $sourceEntity->bussiness_list_id = $bussiness_list_id;
                $sourceEntity->tab = $tab;
                $sourceEntity->section = $section[$j];
                $sourceEntity->current_year = $current_year;
                $sourceEntity->field_name = $field_name[$j];
                $sourceEntity->previous_year_data = !empty($previous_year_data[$j]) ? $previous_year_data[$j] : '';
                $sourceEntity->current_year_data = !empty($current_year_data[$j]) ? $current_year_data[$j] : '';
                $sourceEntity->year_one = !empty($year_one[$j]) ? $year_one[$j] : '';
                $sourceEntity->year_two = !empty($year_two[$j]) ? $year_two[$j] : '';
                $sourceEntity->year_three = !empty($year_three[$j]) ? $year_three[$j] : '';
                $sourceEntity->year_four = !empty($year_four[$j]) ? $year_four[$j] : '';
                $sourceEntity->year_five = !empty($year_five[$j]) ? $year_five[$j] : '';
                $sourceEntity->created = date("Y-m-d h:i:s");
                $sourceEntity->modified = date("Y-m-d h:i:s");
                $BusinessPlans->save($sourceEntity);
            }
        }
    }

    /**
     * @BusinessPlaneAFA
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function BusinessPlaneAFA() {
        $this->autoRender = false;
        //pr($this->request->data);die;
        $BusinessPlans = TableRegistry::get('BusinessPlans');
        $userId = $this->Auth->user('id');

        if ($this->request->is(['post', 'put'])) {

            $field_name = $this->request->data['field_name'];
            $previous_year_data = $this->request->data['previous_year_data'];
            $current_year_data = $this->request->data['current_year_data'];
            $section = $this->request->data['section'];

            $current_year = $this->request->data['current_year'];
            $tab = $this->request->data['tab'];
            $bussiness_list_id = base64_decode($this->request->data['bussiness_list_id']);

            $query = $BusinessPlans->query();
            $query->delete()->where(['tab' => $tab])->where(['user_id' => $userId])->where(['bussiness_list_id' => $bussiness_list_id])->execute();


            $countfield_name = count($field_name);
            for ($j = 0; $j < $countfield_name; $j++) {
                $sourceEntity = $BusinessPlans->newEntity();
                $sourceEntity->user_id = $userId;
                $sourceEntity->bussiness_list_id = $bussiness_list_id;
                $sourceEntity->tab = $tab;
                $sourceEntity->section = $section[$j];
                $sourceEntity->current_year = $current_year;
                $sourceEntity->field_name = $field_name[$j];
                $sourceEntity->previous_year_data = !empty($previous_year_data[$j]) ? $previous_year_data[$j] : '';
                $sourceEntity->current_year_data = !empty($current_year_data[$j]) ? $current_year_data[$j] : '';
                $sourceEntity->created = date("Y-m-d h:i:s");
                $sourceEntity->modified = date("Y-m-d h:i:s");
                $BusinessPlans->save($sourceEntity);
            }
        }
    }

    /**
     * @upload Doc
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function uploadDoc() {

        //pr($_FILES["filename"]);

        //pr($_FILES["filename"]);

        if ($this->request->is(['post', 'put'])) {
            //pr($this->request->data);
            //die;	

	if($this->request->data['businessId'] == 0) {
	    $businessTable = TableRegistry::get('BusinessLists');
	    $businessEntity = $businessTable->newEntity();
	    $businessEntity->status = 0; 
	    $businessList = $businessTable->save($businessEntity);
	    //pr($businessList->id); die;
          }

            $permissionStage = $this->request->data['permissionStage'];
            $blnkarray = array();
            $manualbuyer = isset($this->request->data['manualbuyer']) ? $this->request->data['manualbuyer'] : $blnkarray;
            $businessId = ($this->request->data['businessId']) ? $this->request->data['businessId'] : $businessList->id;
            if ($permissionStage == "Manual") {
                $ps = serialize($manualbuyer);
            } else {
                $ps = serialize($blnkarray);
            }
            $newfilename = $fileName = $filename = '';
            if ($_FILES["filename"]) {
                $string = $this->random_string(10);
                $userId = $this->Auth->user('id');
                $output_dir = WWW_ROOT . 'img' . DS . 'admin_doc' . DS;

                if (isset($_FILES["filename"])) {
                    $fileName = str_replace(' ', '_', $_FILES['filename']["name"]);
                    move_uploaded_file($_FILES['filename']["tmp_name"], $output_dir . $userId . $string . $fileName);
                    $newfilename = $fileName;
                    $filename = $userId . $string . $fileName;
                }

                $title = isset($this->request->data['title']) ? $this->request->data['title'] : $newfilename;

                $BusinessDocumentsyTable = TableRegistry::get('BusinessDocuments');
                $row = $BusinessDocumentsyTable->newEntity();
                $row->url = $filename;
                $row->title = $title;
                $row->file_name = $newfilename;
                $row->is_shown_exc = 1;
                $row->status = ACTIVE;
                $row->share_with = $ps;
                $row->bussiness_list_id = $businessId;
                $row->created = date('Y-m-d');
                $row->modified = date('Y-m-d');
                $row->user_id = $userId;
                $row->permissionStage = $permissionStage;
                $res = $BusinessDocumentsyTable->save($row);
            }


            $this->Flash->set('Document added successfully.', ['params' => ['class' => 'alert success']]);
            return $this->redirect(array('controller' => 'business_lists', 'action' => 'editBusiness/' . base64_encode($businessId).'#docs' ));
        }
    }
    public function getDoc($docid){
	echo $docid; exit;
    }
    /**
     * @Search Doc using ajax
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function EditBusinessSearchDoc() {
        $this->autoRender = false;

        if ($this->request->is(['post', 'put'])) {
            $bussinessId = $this->request->data['bussinessId'];
            $seacrhKey = trim($this->request->data['seacrhKey']);
            $BusinessDocuments = TableRegistry::get('BusinessDocuments');
            $docquery = $BusinessDocuments->find('all');
            $userID = $this->request->session()->read('Auth.User.id');
            if ($seacrhKey != "") {
		$condition['bussiness_list_id'] = $bussinessId;
		$condition['status'] = ACTIVE;
		$condition['OR']['file_name LIKE'] = '%' . $seacrhKey . '%';
		$condition['OR']['title LIKE'] = '%' . $seacrhKey . '%';
                $docdata = $docquery->where($condition);
            } else {
                $docdata = $docquery->where(['bussiness_list_id' => $bussinessId])->where(['status' => ACTIVE]);
            }

            //pr($docdata->toArray());
            $i = 0;
            $users = TableRegistry::get('users');
            $data = array();
	    $permissionStage = [PRENDA=>'PRE-NDA',NDASIGNED=>'NDA-SIGNED',LOISIGNED=>'LOI-SIGNED',DEALCOMPLETE=>'DEAL-COMPLETE',MANUAL=>'MANUAL'];
            foreach ($docdata as $dataa) {
                    $data[$i]['filename'] = !empty($dataa['title']) ? $dataa['title'] : $dataa['file_name'];
                    if ($userID == $dataa['user_id']) {
                        $data[$i]['owner'] = "You";
                    } else {
                        $users_data = $users->find('all')->where(['id' => $dataa['user_id']])->first();
                        $data[$i]['owner'] = $users_data['first_name'] . " " . $users_data['last_name'];
                    }
		    $ext = pathinfo($dataa['file_name'], PATHINFO_EXTENSION);
		    if(strtoupper($ext) == "JPEG" || strtoupper($ext) == "JPG"){
		       $data[$i]['ext'] = webroot.'img/jpg_icon02.png';
		    }elseif(strtoupper($ext) == "DOCX" || strtoupper($ext) == "DOC"){
		       $data[$i]['ext'] = webroot.'img/doc_file.png';
		    }elseif(strtoupper($ext) == "PNG"){
		       $data[$i]['ext'] = webroot.'img/jpg_pic.png';
		    }elseif(strtoupper($ext) == "PDF"){
		       $data[$i]['ext'] = webroot.'img/jpg_pic.png';
		    }elseif(strtoupper($ext) == "XLSX" || strtoupper($ext) == "XLS" ){
		       $data[$i]['ext'] = webroot.'img/xls_icon03.png';
		    } else{
		       $data[$i]['ext'] = webroot.'img/jpg_pic.png';
		    }
		    
		    
		    
                    $share = array(0 => 'No', 1 => 'Yes');
                    $data[$i]['share'] = $share[$dataa['is_shown_exc']];
                    $data[$i]['date'] = date('d/m/y', strtotime($dataa['created']));
                    $data[$i]['permissionStage'] = $permissionStage[$dataa['permissionStage']];
                    $data[$i]['mainID'] = $dataa['id'];
                    $data[$i]['is_shown_exc'] = $dataa['is_shown_exc'];
                    $data[$i++]['docID'] = base64_encode($dataa['id']);
                
            }
        }
        echo json_encode($data);
        exit;
    }

    
    /**
    * @Search Doc using ajax
     *
    * @throws MethodNotAllowedException
    * @throws NotFoundException
    * @param integer $id
    * @return void
    */
    public function SearchDoc(){
	$this->autoRender = false;
	if ($this->request->is(['post', 'put'])) {
	    
	    $bussinessId 	= $this->request->data['bussinessId'];
	    $seacrhKey 		= trim($this->request->data['seacrhKey']);
	    $exchangeSpaceStage	= $this->request->data['exchangeSpaceStage'];
	    
	    $BusinessDocuments 	= TableRegistry::get('BusinessDocuments');
	    $docquery 		= $BusinessDocuments->find('all');
	    
	    $userID 		= $this->request->session()->read('Auth.User.id');
	    
	    if($seacrhKey != ""){
		
		$docdata = $docquery->where(['bussiness_list_id' => $bussinessId,'status'=>ACTIVE,'permissionStage IN'=>[$exchangeSpaceStage,5]])
					    ->andWhere([
					    'OR' => [
					    ['file_name LIKE' =>'%'.$seacrhKey.'%'],
					    ['title LIKE' => '%'.$seacrhKey.'%']
					    ]
					    ]);
	    }else{
		
		$docdata = $docquery->where(['bussiness_list_id' => $bussinessId,
					     'status'=>ACTIVE,
					     'permissionStage IN'=>[$exchangeSpaceStage,5]
					    ]);
	    }
	    $i=0;
	    $users = TableRegistry::get('users');
	    $data = array();
	    $permissionStage = [PRENDA=>'PRE-NDA',NDASIGNED=>'NDA-SIGNED',LOISIGNED=>'LOI-SIGNED',DEALCOMPLETE=>'DEAL-COMPLETE',MANUAL=>'MANUAL'];
	    foreach($docdata as $dataa){
		
		$filename = !empty($dataa['title'])?$dataa['title']:$dataa['file_name'];
		if($dataa->is_shown_exc == 'Yes'){
		    $data[$i]['filename'] = '<a href="'.SITE_URL.'/bussiness_lists/downloadFile/doctype/'.$dataa->url.'">'.$filename.'</a><br>'; 
                }else{
                    $data[$i]['filename'] = $filename;
                }
		
		$ext = pathinfo($dataa['file_name'], PATHINFO_EXTENSION);
		if(strtoupper($ext) == "JPEG" || strtoupper($ext) == "JPG"){
		    $data[$i]['ext'] =  webroot.'img/jpg_icon02.png';
		}elseif(strtoupper($ext) == "DOCX" || strtoupper($ext) == "DOC"){
		    $data[$i]['ext'] = webroot.'img/doc_file.png';
		}elseif(strtoupper($ext) == "PNG"){
		    $data[$i]['ext'] = webroot.'img/jpg_pic.png';
		}elseif(strtoupper($ext) == "PDF"){
		    $data[$i]['ext'] = webroot.'img/jpg_pic.png';
		}elseif(strtoupper($ext) == "XLSX" || strtoupper($ext) == "XLS" ){
		    $data[$i]['ext'] = webroot.'img/xls_icon03.png';
		} else{
		    $data[$i]['ext'] = webroot.'img/jpg_pic.png';
		}
		
		$data[$i]['permissionStage'] = $permissionStage[$dataa->permissionStage];
		$data[$i]['img'] = $dataa->url;
		
		if( $userID == $dataa['user_id']){
		    $data[$i]['owner'] 		= "You";
		}else{
		    $users_data = $users->find('all')->where(['id' =>$dataa['user_id']])->first();
		    $data[$i]['owner'] 			= $users_data['first_name']." ".$users_data['last_name'];
		}
		$data[$i]['date'] 		= date('d/m/y',strtotime($dataa['created']));
		
		$share = array(0=>'No',1=>'Yes');
		
		$data[$i]['share'] 		= $share[$dataa['is_shown_exc']];
		
		$data[$i]['mainID'] 		= $dataa['id'];
		$data[$i]['is_shown_exc'] 	= $dataa['is_shown_exc'];
		$data[$i++]['docID'] 		= base64_encode($dataa['id']);
	    }
	}
	echo json_encode($data);
	exit;
    }
    
    
    
    /**
     * @Search Doc using ajax
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function UpdateDocStatus() {
        $this->autoRender = false;
        if ($this->request->is(['post', 'put'])) {
            $docId = $this->request->data['docId'];
            $status = $this->request->data['status'];
            $changeSatus = array(0 => 1, 1 => 0);

            $BusinessDocuments = TableRegistry::get('BusinessDocuments');
            $BusinessDocumentsEntity = $BusinessDocuments->get($docId);
            $BusinessDocumentsEntity->is_shown_exc = $changeSatus[$status];
            $BusinessDocuments->save($BusinessDocumentsEntity);
        }
        exit;
    }
    
    /**
     * @Edit Doc using ajax
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function editDoc() {
	if ($this->request->is(['post', 'put'])) {
	    $docid 		= $this->request->data['docID'];
	    $bid 		= base64_encode($this->request->data['bid']);
	    $title 		= $this->request->data['title'];
	    $permissionStage 	= $this->request->data['permissionStage'];
	    $manualbuyer = '';
	    if($permissionStage == 5){
		$manualbuyer 	= serialize($this->request->data['manualbuyer']); 
	    }
	    $BusinessDocumentsTable = TableRegistry::get('BusinessDocuments');
	    $query = $BusinessDocumentsTable->query();
	    $query->update()->set([
				   'title'=>$title,
				   'permissionStage'=>$permissionStage,
				   'share_with'=>$manualbuyer
				])
			    ->where(['id' => $docid])
			    ->execute();
	    $this->redirect(array('controller'=>'business_lists','action'=>'editBusiness/'.$bid.'#docs'));
	}
    }

    /**
     * @delete Doc using ajax
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function deleteDoc() {
        $this->autoRender = false;
        if ($this->request->is(['post', 'put'])) {
            $docId = $this->request->data['docId'];
            $BusinessDocumentsTable = TableRegistry::get('BusinessDocuments');
            $docquery = $BusinessDocumentsTable->find('all');
            $docdata = $docquery->where(['id' => $docId]);
	    $docdata = $docdata->toArray();
	    $fileurl = $docdata[0]->url;
	    $output_dir = WWW_ROOT . 'img' . DS . 'admin_doc';
	    unlink($output_dir.'/'.$fileurl);
            $query = $BusinessDocumentsTable->query();
            $query->delete()
                    ->where(['id' => $docId])
                    ->execute();
	}
    }

    public function biz_space() {
        
    }

    /**
     * @send mail for user if notification setting true
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function sendMailForNotifyUser($pageId, $userId) {
        $this->autoRender = false;
        $usersTable = TableRegistry::get('Users');
        $NotificationList = TableRegistry::get('NotificationSettings');
        $excehangeTable = TableRegistry::get('ExchangeSpaces');
        $memberList = TableRegistry::get('ExchangeSpacesMembers');
        $EmailTemplates = TableRegistry::get('EmailTemplates');
        $dataquery = $excehangeTable->find('all');
        $exchagneList = $dataquery->select(['id', 'sender_id'])->where(['bussiness_list_id' => $pageId, 'recevier_id' => $userId])->toArray();
        if (!empty($exchagneList)) {
            foreach ($exchagneList as $exchange) {
                $exID = $exchange->id;
                $senderID = $exchange->sender_id;
                $activation_link = SITE_URL . 'business_lists/listing/';
                if (!empty($senderID)) {
                    $notificationQueryForSender = $NotificationList->find('all')->where(['user_id' => $senderID, 'type_alert' => EXCHANGESPACEALERT, 'alert' => 1])->toArray();
                    $searchResultForSender = $usersTable->find('all')->select(['email', 'id', 'first_name'])->Where(['id' => $senderID])->toArray();
                    foreach ($searchResultForSender as $sender) {
                        if (!empty($notificationQueryForSender)) {
                            $querySender = $EmailTemplates->find('all')
                                    ->where(['EmailTemplates.slug' => 'exchange_space_notification']);
                            $templateSender = $querySender->first();
                            if ($templateSender) {
                                $activation_link = SITE_URL . 'business_lists/listing/';
                                $username = $sender->first_name;
                                $mailMessageSender = str_replace(array('{{username}}', '{{activation_link}}'), array($username, $activation_link), $templateSender->description);
                                //$mailMessageSender = "Document upload in business list";
                                $toSender = $sender->email;
                                $subjectSender = $templateSender->subject;
                                $messageSender = $mailMessageSender;
                                if (parent::sendMail($toSender, $subjectSender, $messageSender)) {
                                    //echo "mail sent to sender";die;
                                }
                            }
                        }
                    }
                }
                $datamember = $memberList->find('all');
                $resultMember = $datamember->select(['member_id'])->where(['exchange_space_id' => $exID])->toArray();
                if (!empty($resultMember)) {
                    foreach ($resultMember as $member) {
                        $memberID = $member->member_id;
                        $searchResult = $usersTable->find('all')->select(['email', 'id', 'first_name'])->Where(['id' => $memberID])->toArray();
                        foreach ($searchResult as $mresult) {
                            $notificationQuery = $NotificationList->find('all')->where(['user_id' => $mresult->id, 'type_alert' => EXCHANGESPACEALERT, 'alert' => 1])->toArray();
                            if (!empty($notificationQuery)) {
                                $query = $EmailTemplates->find('all')
                                        ->where(['EmailTemplates.slug' => 'exchange_space_notification']);
                                $template = $query->first();
                                if ($template) {
                                    $activation_link = SITE_URL . 'business_lists/listing/';
                                    $username = $mresult->first_name;
                                    $mailMessage = str_replace(array('{{username}}', '{{activation_link}}'), array($username, $activation_link), $template->description);


                                    $to = $mresult->email;
                                    $subject = $template->subject;
                                    $message = $mailMessage;
                                    parent::sendMail($to, $subject, $message);
                                }
                            }
                        }
                    }
                }
            }
            return true;
        }
    }

    public function sendMailForFavariteUser($pageId, $wUserId) {
        $this->autoRender = false;
        $usersTable = TableRegistry::get('Users');
        $NotificationList = TableRegistry::get('NotificationSettings');
        $EmailTemplates = TableRegistry::get('EmailTemplates');
        $notificationQuery = $NotificationList->find('all')->where([
                    'user_id' => $wUserId,
                    'OR' => [
                        'type_alert' => MYFAVRAITEALERT,
                        'type_alert' => BUSINESSALERT,
                    ],
                    'alert' => 1
                ])->toArray();
        if (!empty($notificationQuery)) {
            foreach ($notificationQuery as $val) {
                $notifyUser = $val->user_id;
                $searchResult = $usersTable->find('all')->select(['email', 'id', 'first_name'])->Where(['id' => $notifyUser])->toArray();
                foreach ($searchResult as $result) {
                    $query = $EmailTemplates->find('all')
                            ->where(['EmailTemplates.slug' => 'price_change_business']);
                    $template = $query->first();
                    if ($template) {
                        $activation_link = SITE_URL . 'business_lists/listing/';
                        $username = $result->first_name;
                        $mailMessage = str_replace(array('{{username}}', '{{activation_link}}'), array($username, $activation_link), $template->description);
                        $mailMessage = "Price Change in your favoraite business.";
                        $to = $result->email;
                        $subject = $template->subject;
                        $message = $mailMessage;
                        parent::sendMail($to, $subject, $message);
                        return true;
                    }
                }
            }
        }
    }

    public function calculateRadius($zip = null, $distance = null) {
        $zipCodeList = TableRegistry::get('ZipCodes');
        $connection = ConnectionManager::get('default');
        $result = $zipCodeList->find('all')->select(['zip', 'latitude', 'longitude'])->Where(['zip' => $zip])->first();

        if (!empty($result)) {
            $lat = $result['latitude'];
            $lng = $result['longitude'];

            $query = "SELECT *, (3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(latitude * pi()/180) * POWER(SIN(( $lng - longitude) * pi()/180 / 2), 2) ))) as distance from zip_codes as z where 1 having  distance <= " . $distance . " order by distance";
            $resultdata = $connection->execute($query)->fetchAll('assoc');
            $i = 0;
            foreach ($resultdata as $value) {
                $newRow[$i] = $value['zip'];
                $i++;
            }
            return json_encode($newRow);
            exit;
        }
    }

    /**
     * Remove Wish list
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function RemoveWishlist() {
        if ($this->request->is(['post', 'put', 'ajax'])) {

            $id = $_POST['wishlist'];
            $wishlists = TableRegistry::get('Wishlists');
            $rrrr = $wishlists->get($id);
            if ($wishlists->delete($rrrr)) {
                echo "delete";
                die;
            } else {
                echo "sfsdf";
                die;
            }
            //$query = $wishlists->query();
            //$query->delete()->where(['id' => $id])->execute();
        } else {
            
        }
    }

    // genrate random string
    function generateRandomString($length = 6) {
        $characters = '0123456789';
        //$characters = '0123456789asdfghjklqwertyuiopzxcvbnmASDFGHJKLQWERTYUIOPZXCVBNM';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
   
     /**
     * @historical financial
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function historical_financial() {
	if ($this->request->is(['post', 'put'])) {
	    $uid = $this->Auth->user('id');
	    $HistoricalFinancials = TableRegistry::get('HistoricalFinancials');
	    $current_year   		= $this->request->data['current_year'];
	    $quarter_year   		= $this->request->data['quarter_year'];
	    $bid 	    		= $this->request->data['business_list_id'];
	    $historical_financials_id	= $this->request->data['historical_financials_id'];
	    
	    if(!$bid){
		$BussinessLists = TableRegistry::get('BussinessLists');
		$BLEntity = $BussinessLists->newEntity();
		$BLEntity->user_id = $uid;
		$BLEntity->status = INACTIVE;
		$BLEntity->created = date('Y-m-d h:i:s');
		$BLEntity->modified = date('Y-m-d h:i:s');
		$getlastId = $BussinessLists->save($BLEntity);
		$bid = $getlastId->id;
	    }
	    
	    $HFEntity = $HistoricalFinancials->newEntity();
	    if($historical_financials_id){
		$HFEntity->id = $historical_financials_id;
	    }else{
		$HFEntity->created = date('Y-m-d');
	    }
	    $HFEntity->user_id = $uid;
            $HFEntity->business_list_id = $bid;
            $HFEntity->points = 0;
            $HFEntity->current_year = $current_year;
            $HFEntity->quarter_year = $quarter_year;
            $HFEntity->modified = date('Y-m-d');
	    $HistoricalFinancials->save($HFEntity);
	    $this->redirect(array('controller'=>'business_lists','action'=>'editBusiness/'.base64_encode($bid)));
	}
    }
    
     /**
     * @historical financial values
     *
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @param integer $id
     * @return void
     */
    public function historical_financial_values() {
	if($this->request->is(['post', 'put'])) {
	    
	    $bid 				= $this->request->data['business_list_id'];
	    $historical_financials_id 		= $this->request->data['historical_financials_id'];
	    $historical_financials_value_id 	= $this->request->data['historical_financials_value_id'];
	    $datas 				= $this->request->data['data'];
	    $point 				= $this->request->data['point'];
	    
	    $HistoricalFinancialValues 	= TableRegistry::get('HistoricalFinancialValues');
	    $HistoricalFinancial	= TableRegistry::get('HistoricalFinancials');
	    $HFVEntity = $HistoricalFinancialValues->newEntity();
	    $HFEntity = $HistoricalFinancial->newEntity();
	    
	    if($historical_financials_value_id){
		$HFVEntity->id = $historical_financials_value_id;
		$completeData = $HistoricalFinancialValues->get($historical_financials_value_id, ['fields' => ['datas']]);
		$dataOld = unserialize($completeData->datas);
		
		$oldkeys = array_keys($dataOld['revenue']);
		$newkeys =  array_keys($datas['revenue']);
		$diffkeys = array_diff($oldkeys,$newkeys);
		foreach($diffkeys as $diffkey){
		   unset($dataOld['revenue'][$diffkey]);
		}
		
		$oldExpensekeys = array_keys($dataOld['expense']);
		$newExpensekeys =  array_keys($datas['expense']);
		$diffExpensekeys = array_diff($oldExpensekeys,$newExpensekeys);
		foreach($diffExpensekeys as $diffExpensekey){
		   unset($dataOld['expense'][$diffExpensekey]);
		}
		
		$dataNew = array_replace_recursive($dataOld,$datas);
		$dataNew = serialize($dataNew);
		$HFVEntity->datas = $dataNew;
	    }else{
		$HFVEntity->created = date('Y-m-d');
		$HFVEntity->datas = serialize($datas);
	    }
	    $HFVEntity->historical_financials_id = $historical_financials_id;
            $HFVEntity->modified = date('Y-m-d');
	    $HistoricalFinancialValues->save($HFVEntity);
	    
	    $HFEntity->id = $historical_financials_id;
	    $HFEntity->points = $point;
	    $HistoricalFinancial->save($HFEntity);
	    
	    $this->redirect(array('controller'=>'business_lists','action'=>'editBusiness/'.base64_encode($bid)));
	    
	}
	
    }
    

}

