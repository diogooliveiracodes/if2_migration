<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\DB;

// $router->post('/token', function () use ($router){
//     $token = date('y'.'C77656'.'y'.'CC802'.'mm'.'29EC6'.'dy'.'W27TEQ'.'yd'. '0870'.'my'.'E285'.'yd'.'471'); 
//     return $token;
// });


/** Diogo Oliveira - 17-09-2021
 * executa o mapeamento de campos correspondentes do IF2 para o CRM
 * @param json | $db nome do database do cliente
 * @param json | $token token gerado pelo sistema ambos os endpoints
 * @return json | $result lista com os dados mapeados em ambos os db
 */
$router->post('/properties', function () use ($router){
    
    //gerando token de acordo com a data de hoje
    $token = date('y'.'C77656'.'y'.'CC802'.'mm'.'29EC6'.'dy'.'W27TEQ'.'yd'. '0870'.'my'.'E285'.'yd'.'471'); 
    // return $token;
    
    // recebendo token da requisição e db do cliente
    $token_request = $_POST["token"] ?? null;
    $db = $_POST["db"] ?? null;

    //validando token recebido
    if( $token != $_POST["token"]) {
        return response()->json('Token Inválido', 401); 
    } 

    //tentativa de conexão com o banco utilizando PDO e o db fornecido pela requisição
    try{
        $pdo = new PDO('mysql:host=78.47.208.5;dbname='.$db, 'diogo.oliveira', ':4&find&BOOK&6:');
        // $pdo = new PDO('mysql:host=localhost;dbname='.$db, 'root', '');
    } catch (PDOException $Exception){
        return response()->json($Exception->getMessage());
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    //query de consulta ao banco
    $consulta = $pdo->query("select c.name as city_id, p.id, p.reference, p.status, p.situation, p.exclusive, p.position, p.zone,
        p.constructed_year, p.reform_year, p.solar_orientation, p.schedule_visit, p.financing, 
        p.exchange_accept, p.bedroom, p.suite, p.bathroom, p.kitchen, p.vacancy, p.housemaidroom, 
        p.room, p.hobby_box, p.currency, p.main_purpose, p.hide_price, p.valued_sale,
        p.commission_broker, p.valued_rent, p.valued_season, p.exchange_property_value, p.iptu_price, 
        p.iptu_period, p.parcels, p.condo_price, p.usefull_area_measure, p.constructed_area_measure, 
        p.private_area_measure, p.common_area_measure, p.terrain_area_measure, p.total_area_measure, p.condo_id, zipcode,
        p.country, p.estate, p.neighborhood_id, p.street, p.number, p.complement, p.reference,
        p.block, p.lat, p.lng, p.description, p.obs, p.details, p.publish_title, p.web_title, p.seo_tag_title,
        p.seo_url, p.seo_meta_key_words, p.seo_meta_tag_description, p.main_video_url, p.created_at,
        p.updated_at, p.deleted_at
        FROM property p
        left join city c on c.id = p.city_id;");

    
    // criação da lista vazia
    $result=[];

    // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, [
            'old_id'=>utf8_encode($linha['id']),
            'id'=>'',
            'uuid'=>'',
            'companies_id'=>'',
            'unities_id'=>'',
            'unities_id'=>'',
            'owner_id'=>'',
            'tenant_id'=>'',
            'propertie_types_id'=>'',
            'my_reference'=>utf8_encode($linha['reference']),
            'status'=>utf8_encode($linha['status']),
            'reserved'=>'',
            'situation'=>utf8_encode($linha['situation']),
            'exclusive'=>utf8_encode($linha['exclusive']),
            'property_position'=>utf8_encode($linha['position']),
            'property_standard'=>'',
            'location_pattern'=>'',
            'zone'=>utf8_encode($linha['zone']),
            'construction_year'=>utf8_encode($linha['constructed_year']),
            'reform_year'=>utf8_encode($linha['reform_year']),
            'solar_orientation'=>utf8_encode($linha['solar_orientation']),
            'visiting_hours'=>utf8_encode($linha['schedule_visit']),
            'financing'=>utf8_encode($linha['financing']),
            'exchange'=>utf8_encode($linha['exchange_accept']),
            'dorms'=>utf8_encode($linha['bedroom']),
            'suites'=>utf8_encode($linha['suite']),
            'bathrooms'=>utf8_encode($linha['bathroom']),
            'kitchens'=>utf8_encode($linha['kitchen']),
            'vacancies'=>utf8_encode($linha['vacancy']),
            'private_spaces'=>'',
            'covered_spaces'=>'',
            'maid_department'=>utf8_encode($linha['housemaidroom']),
            'rooms'=>utf8_encode($linha['room']),
            'hobby_box'=>utf8_encode($linha['hobby_box']),
            'coin'=>utf8_encode($linha['currency']),
            'main_purpose'=>utf8_encode($linha['main_purpose']),
            'hide_price'=>utf8_encode($linha['hide_price']),
            'sale_value'=>utf8_encode($linha['valued_sale']),
            'sale_commission_rules_id'=>utf8_encode($linha['commission_broker']),
            'rent_value'=>utf8_encode($linha['valued_rent']),
            'rent_commission_rules_id'=>'',
            'season_value'=>utf8_encode($linha['valued_season']),
            'season_commission_rules_id'=>'',
            'exchange_value'=>utf8_encode($linha['exchange_property_value']),
            'exchange_commission_rules_id'=>'',
            'iptu_value'=>utf8_encode($linha['iptu_price']),
            'iptu_period'=>utf8_encode($linha['iptu_period']),
            'portion'=>utf8_encode($linha['parcels']),
            'condominium_value'=>utf8_encode($linha['condo_price']),
            'useful_area'=>utf8_encode($linha['usefull_area_measure']),
            'm2_value_area'=>'',
            'building_area'=>utf8_encode($linha['constructed_area_measure']),
            'private_area'=>utf8_encode($linha['private_area_measure']),
            'common_area'=>utf8_encode($linha['common_area_measure']),
            'land_area'=>'',
            'width_length_area'=>utf8_encode($linha['terrain_area_measure']),
            'freight_fund_area'=>'',
            'total_area'=>utf8_encode($linha['total_area_measure']),
            'condominiums_id'=>utf8_encode($linha['condo_id']),
            'zip_code'=>utf8_encode($linha['zipcode']),
            'country'=>utf8_encode($linha['country']),
            'state'=>utf8_encode($linha['estate']),
            'city'=>utf8_encode($linha['city_id']),
            'neighborhood'=>utf8_encode($linha['neighborhood_id']),
            'street'=>utf8_encode($linha['street']),
            'number'=>utf8_encode($linha['number']),
            'complement'=>utf8_encode($linha['complement']),
            'address_reference'=>utf8_encode($linha['reference']),
            'court_block'=>utf8_encode($linha['block']),
            'batch'=>'',
            'latitude'=>utf8_encode($linha['lat']),
            'longitude'=>utf8_encode($linha['lng']),
            'iptu'=>'',
            'water_bill'=>'',
            'energy_bill'=>'',
            'dwell'=>'',
            'registration'=>'',
            'description'=>utf8_encode($linha['description']),
            'note'=>utf8_encode($linha['obs']),
            'details'=>utf8_encode($linha['details']),
            'title_for_portals'=>utf8_encode($linha['publish_title']),
            'portals'=>'',
            'title_for_site'=>utf8_encode($linha['web_title']),
            'property_group'=>'',
            'seo_property_title'=>utf8_encode($linha['seo_tag_title']),
            'seo_property_url'=>utf8_encode($linha['seo_url']),
            'seo_meta_keywords'=>utf8_encode($linha['seo_meta_key_words']),
            'seo_meta_tag_description'=>utf8_encode($linha['seo_meta_tag_description']),
            'network'=>'',
            'video_url'=>utf8_encode($linha['main_video_url']),
            'created_at'=>utf8_encode($linha['created_at']),
            'updated_at'=>utf8_encode($linha['updated_at']),
            'deleted_at'=>utf8_encode($linha['deleted_at']),
            'key_location_id'=>'',
            'available_to_publish'=>'',
            'user_required_to_publish_id'=>''
        ]);
    }

    return json_encode($result);
});

$router->post('/contact', function () use ($router){
        //gerando token de acordo com a data de hoje
        $token = date('y'.'C77656'.'y'.'CC802'.'mm'.'29EC6'.'dy'.'W27TEQ'.'yd'. '0870'.'my'.'E285'.'yd'.'471'); 
        // return $token;
        
        // recebendo token da requisição e db do cliente
        $token_request = $_POST["token"] ?? null;
        $db = $_POST["db"] ?? null;
    
        //validando token recebido
        if( $token != $_POST["token"]) {
            return response()->json('Token Inválido', 401); 
        } 
    
        //tentativa de conexão com o banco utilizando PDO e o db fornecido pela requisição
        try{
            $pdo = new PDO('mysql:host=78.47.208.5;dbname='.$db, 'diogo.oliveira', ':4&find&BOOK&6:');
        } catch (PDOException $Exception){
            return response()->json($Exception->getMessage());
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //query de consulta ao banco
        $consulta = $pdo->query("SELECT c.name, c.main_email, c.main_phone, c.main_phone, c.cpf, c.rg, c.rg_dispatched_at,
        c.rg_dispatcher, c.occupation, c.birthday, c.gender, c.civil_status, c.partner_name, c.nationality, c.zipcode,
        c.estate, c.city, c.neighborhood, c.street, c.number, c.complement, c.created_at, c.updated_at, c.deleted_at, c.main_phone
        FROM customer c;");

        // criação da lista vazia
        $result=[];

        // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
        while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
            array_push($result, [
                'id' => '',
                'uuid' => '',
                'status' => '',
                'companies_id' => '',
                'unities_id' => '',
                'users_id' => '',
                'name' => utf8_encode($linha['name']),
                'email' => utf8_encode($linha['main_email']),
                'second_email' => '',
                'commercial_phone' => utf8_encode($linha['main_phone']),
                'home_phone' => utf8_encode($linha['main_phone']),
                'cpf' => utf8_encode($linha['cpf']),
                'rg' => utf8_encode($linha['rg']),
                'issue_date' => utf8_encode($linha['rg_dispatched_at']),
                'emitting_organ' => utf8_encode($linha['rg_dispatcher']),
                'occupation' => utf8_encode($linha['occupation']),
                'income_brackets_id' => '',
                'birthday' => utf8_encode($linha['birthday']),
                'gender' => utf8_encode($linha['gender']),
                'civil_status' => utf8_encode($linha['civil_status']),
                'spouse' => utf8_encode($linha['partner_name']),
                'nationality' => utf8_encode($linha['nationality']),
                'zip_code' => utf8_encode($linha['zipcode']),
                'state' => utf8_encode($linha['estate']),
                'city' => utf8_encode($linha['city']),
                'neighborhood' => utf8_encode($linha['neighborhood']),
                'street' => utf8_encode($linha['street']),
                'number' => utf8_encode($linha['number']),
                'complement' => utf8_encode($linha['complement']),
                'reference' => '',
                'created_at' => utf8_encode($linha['created_at']),
                'updated_at' => utf8_encode($linha['updated_at']),
                'deleted_at' => utf8_encode($linha['deleted_at']),
                'contact_types_id' => '',
                'cell_phone' => utf8_encode($linha['main_phone'])
            ]);
        }

    return json_encode($result);
});


$router->post('/condominiums', function () use ($router){
    //gerando token de acordo com a data de hoje
    $token = date('y'.'C77656'.'y'.'CC802'.'mm'.'29EC6'.'dy'.'W27TEQ'.'yd'. '0870'.'my'.'E285'.'yd'.'471'); 
    // return $token;
    
    // recebendo token da requisição e db do cliente
    $token_request = $_POST["token"] ?? null;
    $db = $_POST["db"] ?? null;

    //validando token recebido
    if( $token != $_POST["token"]) {
        return response()->json('Token Inválido', 401); 
    } 

    //tentativa de conexão com o banco utilizando PDO e o db fornecido pela requisição
    try{
        $pdo = new PDO('mysql:host=78.47.208.5;dbname='.$db, 'diogo.oliveira', ':4&find&BOOK&6:');
    } catch (PDOException $Exception){
        return response()->json($Exception->getMessage());
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //query de consulta ao banco
    $consulta = $pdo->query("SELECT name, floors, zipcode, country, estate, city,
    neighborhood, street, number, complement, landmark, created_at, updated_at, deleted_at
    FROM condo;");

    // criação da lista vazia
    $result=[];

    // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, [

            'id' => '',
            'uuid' => '',
            'status' => '',
            'companies_id' => '',
            'unities_id' => '',
            'name' => utf8_encode($linha['name']),
            'tower' => '',
            'floor' => utf8_encode($linha['floors']),
            'unity' => '',
            'zip_code' => utf8_encode($linha['zipcode']),
            'country' => utf8_encode($linha['country']),
            'state' => utf8_encode($linha['estate']),
            'city' => utf8_encode($linha['city']),
            'neighborhood' => utf8_encode($linha['neighborhood']),
            'street' => utf8_encode($linha['street']),
            'number' => utf8_encode($linha['number']),
            'complement' => utf8_encode($linha['complement']),
            'reference' => utf8_encode($linha['landmark']),
            'court_block' => '',
            'batch' => '',
            'created_at' => utf8_encode($linha['created_at']),
            'updated_at' => utf8_encode($linha['updated_at']),
            'deleted_at' => utf8_encode($linha['deleted_at']),

        ]);
    }

return json_encode($result);
});

$router->post('/photo', function () use ($router){
    //gerando token de acordo com a data de hoje
    $token = date('y'.'C77656'.'y'.'CC802'.'mm'.'29EC6'.'dy'.'W27TEQ'.'yd'. '0870'.'my'.'E285'.'yd'.'471'); 
    
    // recebendo token da requisição e db do cliente
    $token_request = $_POST["token"] ?? null;
    $db = $_POST["db"] ?? null;

    //validando token recebido
    if( $token != $_POST["token"]) {
        return response()->json('Token Inválido', 401); 
    } 

    // capturando o nome do cliente
    $cliente = str_replace('_if2', '', $db);

    //tentativa de conexão com o banco utilizando PDO e o db fornecido pela requisição
    try{
        $pdo = new PDO('mysql:host=78.47.208.5;dbname='.$db, 'diogo.oliveira', ':4&find&BOOK&6:');
    } catch (PDOException $Exception){
        return response()->json($Exception->getMessage());
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT f.id, f.name 
        FROM photo f 
        inner join property p on p.id = f.property_id
        where p.status != 2
        order by p.status desc;
    ";

    //query de consulta ao banco
    $consulta = $pdo->query("SELECT f.id, f.name, f.width, f.height, f.thumb_width, f.property_id, f._order 
    FROM photo f;");



    // criação da lista vazia
    $result=[];

    // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, [

            'id' => utf8_encode($linha['id']),
            'name' => utf8_encode($linha['name']),
            'url' => 'https://static.if2.com.br/acc/'.$cliente.'/photos/'.$linha['property_id'].'/'.$linha['name'].'.jpg',
            // 'width' => utf8_encode($linha['width']),
            // 'height' => utf8_encode($linha['height']),
            // 'thumb_width' => utf8_encode($linha['thumb_width']),
            'property_id' => utf8_encode($linha['property_id']),
            'order' => utf8_encode($linha['_order'])
        ]);
    }

return json_encode($result);
});
