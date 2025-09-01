<?php

//as www-data
//cd /var/www/html/cake4/rd_cake && bin/cake voucher 
namespace App\Shell;

use Cake\Console\Shell;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\FrozenTime;
use Cake\Http\Exception;

class VoucherShell extends Shell {

    //This shell runs at longer intervals (15 min) to check for two things.
    //It checks all the new and used vouchers and then see if:
   //It has **Rd-Voucher** attribute it will mark it as depleted if the time is up
   //It has **Expiration** attribute it will mark the voucher as expired if it is passed the expiration date
   
    public function initialize():void{
        parent::initialize();
        $this->loadModel('Vouchers');
        $this->loadModel('Clouds');
        $this->loadModel('Users');
        $this->loadModel('Radaccts');
    }
    
    public $tasks = ['Usage', 'Counters'];  // Ajouter le task Counters
    // public $tasks   = ['Usage'];

    public function main() {
        $qr = $this->{'Vouchers'}->find()
            ->where(['OR'=> [['Vouchers.status' => 'new'],['Vouchers.status' => 'used']]])
            ->all();
        foreach($qr as $i){
            $this->process_voucher($i->name);
        }
    }

    // private function process_voucher($name){

    //     $this->out("<info>Voucher => $name</info>");

    //     //Test for depleted
	// 	$ret_val 				= $this->Usage->time_left_from_login($name);

	// 	$time_left_from_login 	= $ret_val[0];
	// 	$time_avail 			= $ret_val[1];

    //     if($time_left_from_login){
    //         if($time_left_from_login == 'depleted'){
    //             //Mark time usage as 100% and voucher as depleted
    //             $q_r = $this->{'Vouchers'}->find()->where(['Vouchers.name' => $name])->first();
    //             if($q_r){
    //                 $d = [];
    //                 $d['perc_time_used'] = 100;
    //                 $d['status']         = 'depleted';
	// 				if($time_avail){
	// 					$d['time_cap']       = $time_avail;
	// 					$d['time_used']      = $time_avail; //Make them equal
	// 				}
    //                 $this->{'Vouchers'}->patchEntity($q_r,$d);
    //                 $this->{'Vouchers'}->save($q_r);
    //             }
    //         }else{
	// 			if($time_avail){
	// 				$time_used 	= $time_avail - $time_left_from_login;
	// 				$q_r = $this->{'Vouchers'}->find()->where(['Vouchers.name' => $name])->first();
	// 			    if($q_r){
	// 			        $d = [];
	// 					$d['time_cap']       = $time_avail;
	// 					$d['time_used']      = $time_used; //Make them equal
	// 			        $this->{'Vouchers'}->patchEntity($q_r,$d);
    //                     $this->{'Vouchers'}->save($q_r);
	// 			    }
	// 			}
	// 		}
    //     }

    //     //Test for expired
    //      $time_left_from_expire = $this->Usage->time_left_from_expire($name);
    //     if($time_left_from_expire){
    //         if($time_left_from_expire == 'expired'){
    //             //Mark time usage as 100% and voucher as expired
    //             $q_r = $this->{'Vouchers'}->find()->where(['Vouchers.name' => $name])->first();
    //             if($q_r){
    //                 $d = [];
    //                 $d['perc_time_used'] = 100;
    //                 $d['status']         = 'expired';
    //                 $this->{'Vouchers'}->patchEntity($q_r,$d);
    //                 $this->{'Vouchers'}->save($q_r);
    //             }
    //         }
    //     }
    // }
    
   

    private function process_voucher($name){

        $this->out("<info>Voucher => $name</info>");
        
        $kick_needed = false;
        $kick_reason = '';

        // Récupérer le voucher une seule fois
        $q_r = $this->{'Vouchers'}->find()->where(['Vouchers.name' => $name])->first();
        if(!$q_r){
            $this->out("<error>Voucher $name not found</error>");
            return;
        }

        // =====================================
        // TEST 1: VERIFICATION TIME (DEPLETED)
        // =====================================
        $ret_val = $this->Usage->time_left_from_login($name);
        $time_left_from_login = $ret_val[0];
        $time_avail = $ret_val[1];

        if($time_left_from_login){
            if($time_left_from_login == 'depleted'){
                // Vérifier si le statut va changer
                if($q_r->status != 'depleted'){
                    $kick_needed = true;
                    $kick_reason = 'TIME_DEPLETED';
                }
                
                //Mark time usage as 100% and voucher as depleted
                $d = [];
                $d['perc_time_used'] = 100;
                $d['status'] = 'depleted';
                if($time_avail){
                    $d['time_cap'] = $time_avail;
                    $d['time_used'] = $time_avail;
                }
                $this->{'Vouchers'}->patchEntity($q_r,$d);
                $this->{'Vouchers'}->save($q_r);
                
            }else{
                if($time_avail){
                    $time_used = $time_avail - $time_left_from_login;
                    $d = [];
                    $d['time_cap'] = $time_avail;
                    $d['time_used'] = $time_used;
                    $this->{'Vouchers'}->patchEntity($q_r,$d);
                    $this->{'Vouchers'}->save($q_r);
                }
            }
        }

        // =====================================
        // TEST 2: VERIFICATION TIME (EXPIRED)
        // =====================================
        if(!$kick_needed){ // Seulement si pas encore marqué pour kick
            $time_left_from_expire = $this->Usage->time_left_from_expire($name);
            if($time_left_from_expire){
                if($time_left_from_expire == 'expired'){
                    // Vérifier si le statut va changer
                    if($q_r->status != 'expired' && $q_r->status != 'depleted'){
                        $kick_needed = true;
                        $kick_reason = 'TIME_EXPIRED';
                    }
                    
                    //Mark time usage as 100% and voucher as expired
                    $d = [];
                    $d['perc_time_used'] = 100;
                    $d['status'] = 'expired';
                    $this->{'Vouchers'}->patchEntity($q_r,$d);
                    $this->{'Vouchers'}->save($q_r);
                }
            }
        }

        // =====================================
        // TEST 3: VERIFICATION DATA USAGE
        // =====================================
        if(!$kick_needed){ // Seulement si pas encore marqué pour kick
            // Trouver le profil du voucher
            $profile = $this->_find_user_profile($name);
            if($profile){
                // Utiliser le task Counters pour obtenir les compteurs de données
                $counters = $this->Counters->return_counter_data($profile, 'voucher', $name);
                
                if(array_key_exists('data', $counters)){
                    // Calculer l'utilisation des données
                    $data_usage = $this->Usage->data_usage($counters['data'], $name, 'username');
                    $data_limit = $counters['data']['value'];
                    
                    $this->out("<info>Data usage: $data_usage / $data_limit</info>");
                    
                    // Vérifier si la limite de données est atteinte (100% ou plus)
                    if($data_usage >= $data_limit){
                        // Vérifier si le statut va changer
                        if($q_r->status != 'depleted'){
                            $kick_needed = true;
                            $kick_reason = 'DATA_DEPLETED';
                        }
                        
                        // Marquer comme depleted à cause des données
                        $d = [];
                        $d['perc_data_used'] = 100;
                        $d['status'] = 'depleted';
                        $d['data_used'] = intval($data_usage);
                        $d['data_cap'] = $data_limit;
                        $this->{'Vouchers'}->patchEntity($q_r,$d);
                        $this->{'Vouchers'}->save($q_r);
                        
                    }else{
                        // Mettre à jour le pourcentage d'utilisation des données
                        $perc_data_used = intval(($data_usage / $data_limit) * 100);
                        $d = [];
                        $d['perc_data_used'] = $perc_data_used;
                        $d['data_used'] = intval($data_usage);
                        $d['data_cap'] = $data_limit;
                        if($q_r->status == 'new'){
                            $d['status'] = 'used';
                        }
                        $this->{'Vouchers'}->patchEntity($q_r,$d);
                        $this->{'Vouchers'}->save($q_r);
                    }
                }
            }
        }

        // =====================================
        // EXECUTER LE KICK OFF SI NECESSAIRE
        // =====================================
        if($kick_needed){
            $this->out("<warning>Voucher $name needs to be kicked off - Reason: $kick_reason</warning>");
            $this->kickOffVoucher($name, $q_r->cloud_id, $kick_reason);
        } else {
            // Log pour monitoring (optionnel)
            $status = $q_r->status;
            $time_perc = isset($q_r->perc_time_used) ? $q_r->perc_time_used : 0;
            $data_perc = isset($q_r->perc_data_used) ? $q_r->perc_data_used : 0;
            $this->out("<info>Voucher $name status: $status (Time: {$time_perc}%, Data: {$data_perc}%)</info>");
        }
    }

    /**
     * Méthode pour déconnecter (kick off) un voucher
     */
    private function kickOffVoucher($username, $cloud_id, $reason = 'UNKNOWN'){
        
        // Vérifier si l'utilisateur est actuellement connecté
        $e_ra = $this->{'Radaccts'}->find()
            ->where(['Radaccts.username' => $username, 'Radaccts.acctstoptime IS NULL'])
            ->first();
            
        if(!$e_ra){
            $this->out("<info>User $username is not currently connected - No kick off needed</info>");
            return;
        }
        
        $this->out("<warning>User $username is currently connected - Proceeding with kick off (Reason: $reason)</warning>");
        
        // Récupérer les informations du cloud
        $e_cloud = $this->{'Clouds'}->find()->where(['Clouds.id' => $cloud_id])->first();
        if(!$e_cloud){
            $this->out("<error>Cloud not found for voucher $username</error>");
            return;
        }
        
        // Récupérer les informations de l'utilisateur propriétaire du cloud
        $user_id = $e_cloud->user_id;
        $e_user = $this->{'Users'}->find()->where(['Users.id' => $user_id])->first();
        if(!$e_user){
            $this->out("<error>User not found for cloud $cloud_id</error>");
            return;
        }
        
        $token = $e_user->token;
        $url = 'http://127.0.0.1/cake4/rd_cake/radaccts/kick-active-username.json';
        $request = [
            'cloud_id' => $cloud_id,
            'username' => $username,
            'token' => $token
        ];
        
        try {
            $http = new \Cake\Http\Client();
            $response = $http->get($url, $request, ['type' => 'json']);
            $reply = $response->getStringBody();
            
            $this->out("<success>Kick off request sent for $username (Reason: $reason)</success>");
            $this->out("<info>Response: $reply</info>");
            
        } catch (Exception $e) {
            $this->out("<error>Failed to kick off user $username: " . $e->getMessage() . "</error>");
        }
    }

    /**
     * Méthode pour trouver le profil d'un utilisateur
     */
    private function _find_user_profile($username){
        $this->loadModel('Radchecks');
        $profile = false;
        $q_r = $this->Radchecks->find()
            ->where(['Radchecks.username' => $username,'Radchecks.attribute' => 'User-Profile'])
            ->first();
        if($q_r){
            $profile = $q_r->value;
        }
        return $profile;
    }
}

?>
