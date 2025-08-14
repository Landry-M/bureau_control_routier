<?php


namespace Control;


use Model\Db;
use ORM;

class NotificationsController extends Db
{

    //
    public function sign_presence($scan_data, $id_user)
    {

        //extraire id agent des scan data
        $id_agent = explode(':', $scan_data)[2];

        $today_date = date('Y-m-d');

        $this->getConnexion();

        $user = ORM::for_table('notifications')
            ->where(array('id_agent'=>$id_agent, 'd_arrive' => $today_date, ))
            //->where_gte('created_at', $today_date)
            ->find_one();

        $today_time = date('H:i:s');

        if($user)
        {
            $user->set(array(
                'h_depart' => $today_time,
                'd_depart' => $today_date,
            ));
            $user->save();
        }else{
            $user = ORM::for_table('notifications')->create();
            $user->id_agent = $id_agent;
            $user->id_user = $id_user;
            $user->h_arrive = $today_time;
            $user->d_arrive = $today_date;
            $user->save();
        }

        $result['state'] = true;
        $result['data'] = $user;
//        $person = ORM::for_table('entreprises')->create();
//        $person->id_agent = $id_agent;
//        $person->id_user = $id_user;
//        $person->h_arrive = $arrive;
//        $person->h_depart = $depart;
//
//        $person->save();

        return json_encode($result);
    }

}