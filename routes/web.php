<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\DB;

/**
 * Diogo Oliveira - 11-10-21
 * Percorre cada elemento
 * @param array | $result lista sem filtrar
 * @return array | $filtrado lista filtrada
 */
// function myMapeer($result){
//     $filtrado = [];
//     foreach($result as $key => $value){
//         $filtrado[$key]=[];
//         foreach($value as $k => $v){
//             $filtrado[$key]+=[$k => $v];
//         }
//     }
//     return $filtrado;
// }

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
    $consulta = $pdo->query("SELECT group_concat(cp.customer_id separator ', ') as customers, c.name as city_id, p.id,
        p.reference, p.status, p.situation, p.exclusive, p.position, p.zone,
        p.constructed_year, p.reform_year, p.solar_orientation, p.schedule_visit, p.financing,
        p.exchange_accept, p.bedroom, p.suite, p.bathroom, p.kitchen, p.vacancy, p.vacancy_private, p.vacancy_cover, p.housemaidroom,
        p.room, p.hobby_box, p.currency, p.main_purpose, p.hide_price, p.valued_sale,
        p.commission_broker, p.valued_rent, p.valued_season, p.exchange_property_value, p.iptu_price,
        p.iptu_period, p.parcels, p.condo_price, p.usefull_area_min, p.constructed_area_min,
        p.private_area_min, p.common_area_min, p.terrain_area_min, p.total_area_min, p.condo_id, p.zipcode,
        p.country, p.estate, p.neighborhood_id, p.street, p.number, p.complement, p.reference,
        p.block, p.lat, p.lng, p.description, p.obs, p.details, p.publish_title, p.web_title, p.seo_tag_title,
        p.seo_url, p.seo_meta_key_words, p.seo_meta_tag_description, p.main_video_url, p.created_at,
        p.updated_at, p.deleted_at, p.key_local, p.sale_price_min, p.rent_price_min
        FROM property p
        left join city c on c.id = p.city_id
        left join customer_property cp on cp.property_id = p.id
        group by p.id;");


    // criação da lista vazia
    $result=[];

    // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, [
            'old_id'=>$linha['id'],
            'id'=>'',
            'uuid'=>'',
            'companies_id'=>'',
            'unities_id'=>'',
            'unities_id'=>'',
            'owner_id'=>$linha['customers'],
            'tenant_id'=>'',
            'propertie_types_id'=>'',
            'my_reference'=>$linha['reference'],
            'status'=>$linha['status'],
            'reserved'=>'',
            'situation'=>$linha['situation'],
            'exclusive'=>$linha['exclusive'],
            'property_position'=>$linha['position'],
            'property_standard'=>'',
            'location_pattern'=>'',
            'zone'=>$linha['zone'],
            'construction_year'=>$linha['constructed_year'],
            'reform_year'=>$linha['reform_year'],
            'solar_orientation'=>$linha['solar_orientation'],
            'visiting_hours'=>$linha['schedule_visit'],
            'financing'=>$linha['financing'],
            'exchange'=>$linha['exchange_accept'],
            'dorms'=>$linha['bedroom'],
            'suites'=>$linha['suite'],
            'bathrooms'=>$linha['bathroom'],
            'kitchens'=>$linha['kitchen'],
            'vacancies'=>$linha['vacancy'],
            'private_spaces'=>$linha['vacancy_private'],
            'covered_spaces'=>$linha['vacancy_cover'],
            'maid_department'=>$linha['housemaidroom'],
            'rooms'=>$linha['room'],
            'hobby_box'=>$linha['hobby_box'],
            'coin'=>$linha['currency'],
            'main_purpose'=>$linha['main_purpose'],
            'hide_price'=>$linha['hide_price'],
            'sale_value'=>$linha['sale_price_min'],
            'sale_commission_rules_id'=>$linha['commission_broker'],
            'rent_value'=>$linha['rent_price_min'],
            'rent_commission_rules_id'=>'',
            'season_value'=>$linha['valued_season'],
            'season_commission_rules_id'=>'',
            'exchange_value'=>$linha['exchange_property_value'],
            'exchange_commission_rules_id'=>'',
            'iptu_value'=>$linha['iptu_price'],
            'iptu_period'=>$linha['iptu_period'],
            'portion'=>$linha['parcels'],
            'condominium_value'=>$linha['condo_price'],
            'useful_area'=>$linha['usefull_area_min'],
            'm2_value_area'=>'',
            'building_area'=>$linha['constructed_area_min'],
            'private_area'=>$linha['private_area_min'],
            'common_area'=>$linha['common_area_min'],
            'land_area'=>'',
            'width_length_area'=>$linha['terrain_area_min'],
            'freight_fund_area'=>'',
            'total_area'=>$linha['total_area_min'],
            'condominiums_id'=>$linha['condo_id'],
            'zip_code'=>$linha['zipcode'],
            'country'=>$linha['country'],
            'state'=>$linha['estate'],
            'city'=>$linha['city_id'],
            'neighborhood'=>$linha['neighborhood_id'],
            'street'=>$linha['street'],
            'number'=>$linha['number'],
            'complement'=>$linha['complement'],
            'address_reference'=>$linha['reference'],
            'court_block'=>$linha['block'],
            'batch'=>'',
            'latitude'=>$linha['lat'],
            'longitude'=>$linha['lng'],
            'iptu'=>'',
            'water_bill'=>'',
            'energy_bill'=>'',
            'dwell'=>'',
            'registration'=>'',
            'description'=>html_entity_decode(html_entity_decode($linha['description'])),
            'note'=>$linha['obs'],
            'details'=>$linha['details'],
            'title_for_portals'=>$linha['publish_title'],
            'portals'=>'',
            'title_for_site'=>$linha['web_title'],
            'property_group'=>'',
            'seo_property_title'=>$linha['seo_tag_title'],
            'seo_property_url'=>$linha['seo_url'],
            'seo_meta_keywords'=>$linha['seo_meta_key_words'],
            'seo_meta_tag_description'=>$linha['seo_meta_tag_description'],
            'network'=>'',
            'video_url'=>$linha['main_video_url'],
            'created_at'=>$linha['created_at'],
            'updated_at'=>$linha['updated_at'],
            'deleted_at'=>$linha['deleted_at'],
            'key_location_id'=>$linha['key_local'],
            'available_to_publish'=>'',
            'user_required_to_publish_id'=>''
        ]);
    }

    return response()->json($result);
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
                'name' => $linha['name'],
                'email' => $linha['main_email'],
                'second_email' => '',
                'commercial_phone' => $linha['main_phone'],
                'home_phone' => $linha['main_phone'],
                'cpf' => $linha['cpf'],
                'rg' => $linha['rg'],
                'issue_date' => $linha['rg_dispatched_at'],
                'emitting_organ' => $linha['rg_dispatcher'],
                'occupation' => $linha['occupation'],
                'income_brackets_id' => '',
                'birthday' => $linha['birthday'],
                'gender' => $linha['gender'],
                'civil_status' => $linha['civil_status'],
                'spouse' => $linha['partner_name'],
                'nationality' => $linha['nationality'],
                'zip_code' => $linha['zipcode'],
                'state' => $linha['estate'],
                'city' => $linha['city'],
                'neighborhood' => $linha['neighborhood'],
                'street' => $linha['street'],
                'number' => $linha['number'],
                'complement' => $linha['complement'],
                'reference' => '',
                'created_at' => $linha['created_at'],
                'updated_at' => $linha['updated_at'],
                'deleted_at' => $linha['deleted_at'],
                'contact_types_id' => '',
                'cell_phone' => $linha['main_phone']
            ]);
        }

    return response()->json($result);
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
    $consulta = $pdo->query("SELECT id, name, floors, zipcode, country, estate, city,
    neighborhood, street, number, complement, landmark, created_at, updated_at, deleted_at
    FROM condo;");

    // criação da lista vazia
    $result=[];

    // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        array_push($result, [

            'id' => '',
            'old_id' => $linha['id'],
            'uuid' => '',
            'status' => '',
            'companies_id' => '',
            'unities_id' => '',
            'name' => $linha['name'],
            'tower' => '',
            'floor' => $linha['floors'],
            'unity' => '',
            'zip_code' => $linha['zipcode'],
            'country' => $linha['country'],
            'state' => $linha['estate'],
            'city' => $linha['city'],
            'neighborhood' => $linha['neighborhood'],
            'street' => $linha['street'],
            'number' => $linha['number'],
            'complement' => $linha['complement'],
            'reference' => $linha['landmark'],
            'court_block' => '',
            'batch' => '',
            'created_at' => $linha['created_at'],
            'updated_at' => $linha['updated_at'],
            'deleted_at' => $linha['deleted_at'],

        ]);
    }

    return response()->json($result);
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

            'id' => $linha['id'],
            'name' => $linha['name'],
            'url' => 'https://static.if2.com.br/acc/'.$cliente.'/photos/'.$linha['property_id'].'/'.$linha['name'].'.jpg',
            // 'width' => $linha['width'],
            // 'height' => $linha['height'],
            // 'thumb_width' => $linha['thumb_width'],
            'property_id' => $linha['property_id'],
            'order' => $linha['_order']
        ]);
    }

    return response()->json($result);
});

$router->post('/customer', function () use ($router){
    //gerando token de acordo com a data de hoje
    $token = date('y'.'C77656'.'y'.'CC802'.'mm'.'29EC6'.'dy'.'W27TEQ'.'yd'. '0870'.'my'.'E285'.'yd'.'471');
    // return $token;

    // recebendo token da requisição e db do cliente
    $token_request = $_POST["token"] ?? null;
    $db = $_POST["db"] ?? null;

    //validando token recebido
    if( $token != $token_request) {
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
    $consulta = $pdo->query("SELECT c.id, c.name, c.cpf, c.main_phone, c.zipcode, c.estate, c.city,
        c.neighborhood, c.street, c.number, c.complement, c.obs, c.created_at, c.updated_at, c.deleted_at, c.type
        FROM customer c;");

    // criação da lista vazia
    $result=[];

    // percorrendo a lista com os resultados da consulta e retornando uma lista adaptada ao banco do CRM
    while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {

        // de->para com tipo de pessoa Física/Jurídica
        if($linha['type']==0 || $linha['type']==1){
            $typeFiltered = 0;
        } else {
            $typeFiltered = 1;
        }

        array_push($result, [
            'old_id' => $linha['id'],
            'id' => '',
            'uuid' => '',
            'status' => '',
            'companies_id' => '',
            'unities_id' => '',
            'users_id' => '',
            'type_person' => $typeFiltered,
            'domain' => '',
            'name' => $linha['name'],
            'cpf' => $linha['cpf'],
            'phone' => $linha['main_phone'],
            'type' => 1,
            'zip_code' => $linha['zipcode'],
            'state' => $linha['estate'],
            'city' => $linha['city'],
            'neighborhood' => $linha['neighborhood'],
            'street' => $linha['street'],
            'number' => $linha['number'],
            'complement' => $linha['complement'],
            'reference' => '',
            'note' => $linha['obs'],
            'created_at' => $linha['created_at'],
            'updated_at' => $linha['updated_at'],
            'deleted_at' => $linha['deleted_at']
        ]);
    }

    return response()->json($result);
});
