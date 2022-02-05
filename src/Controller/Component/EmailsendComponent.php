<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

ini_set('memory_limit', '-1');

class EmailSendComponent extends Component {

    public function BookingTimeEmailSend($booking_id,$user_id = null){
		echo $booking_id;
		echo "<br>";
		
		use Cake\ORM\TableRegistry;
        $this->Articles = TableRegistry::get('Articles');
		$this->loadModel('Bookings');
		$this->Bookings->belongsTo('Users',['foreignKey' => 'user_id']);
		
		$bookings = $this->Bookings->get($booking_id)->contain(['BookingDetails','Users'=>array('fields'=>['Users.first_name','Users.last_name'])])->toArray();
		pr($bookings); die;
		
		
		//$bookings = $this->Bookings->find('all',['conditions' => ['Bookings.worker_id' => $id,'Bookings.payment_status'=>'Completed']])->contain(['BookingDetails','Users'=>array('fields'=>['Users.first_name','Users.last_name'])])->group('Bookings.id')->toArray();
		
		
		
		
		
		
		
		
        $this->loadModel('Templates');
		$this->Templates->hasOne('TemplateDetails', ['foreignKey' => 'template_id', 'conditions' => array('language_id' => Configure::read('DefaultLanguage'))]);
		$template = $this->Templates->findBySlug('admin_coach_registration')->contain(['TemplateDetails'])->toArray();
		$email_subject = $template[0]['template_detail']->subject;

		$subject = __('' . Configure::read('Site.Title') . ' ' . $email_subject . '', true);
		$message = str_replace(array('{NAME}', '{SITE}', '{USERNAME}', '{PASSWORD}'), array(ucfirst($this->request->data['first_name'] . ' ' . $this->request->data['last_name']), Configure::read('Site.Title'), $this->request->data['email'], $this->request->data['password']), $template[0]['template_detail']->content);

		parent::sendMail($this->request->data['email'], $subject, $message, array(Configure::read('AdminEmail') => Configure::read('Site.Title')));
       
		
	}

}

?>