<?php

namespace App\Gitea;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Log;

class GiteaClient
{
    private static Client $cliente;
    private static array $headers;

    private static function init()
    {
        self::$cliente = new Client(['base_uri' => config('gitea.url') . '/api/v1/']);

        self::$headers = [
            'Authorization' => 'token ' . config('gitea.token'),
            'Accept' => 'application/json',
        ];
    }

    public static function repo($repositorio)
    {
        self::init();

        $request = self::$cliente->get('repos/' . $repositorio, [
            'headers' => self::$headers
        ]);

        $response = json_decode($request->getBody(), true);

        $data = [
            'id' => $response['id'],
            'name' => $response['name'],
            'description' => $response['description'],
            'http_url_to_repo' => $response['clone_url'],
            'path_with_namespace' => $response['full_name'],
            'web_url' => $response['html_url']
        ];

        return $data;
    }

    public static function clone($repositorio, $username, $destino)
    {
        self::init();

        // Obtener el ID de usuario de destino
        $request = self::$cliente->get('users/' . $username, [
            'headers' => self::$headers
        ]);

        $response = json_decode($request->getBody(), true);
        $uid = $response['id'];

        try {// Hacer la copia del repositorio
            $request = self::$cliente->post('repos/migrate', [
                'headers' => self::$headers,
                'form_params' => [
                    "auth_username" => config('gitea.user'),
                    "auth_password" => config('gitea.password'),
                    "clone_addr" => config('gitea.url') . '/' . $repositorio . '.git',
                    "uid" => $uid,
                    "repo_name" => $destino,
                    "private" => true,
                ]
            ]);
        } catch (\Exception $e) {
        }

        if ($request->getStatusCode() == 201) {
            $response = json_decode($request->getBody(), true);

            $data = [
                'id' => $response['id'],
                'name' => $response['name'],
                'description' => $response['description'],
                'http_url_to_repo' => $response['clone_url'],
                'path_with_namespace' => $response['full_name'],
                'web_url' => $response['html_url']
            ];

            return $data;
        } else {
            return 409;
        }
    }

    public static function dump_gitlab($repositorio, $username, $destino)
    {
        self::init();

        // Obtener el ID de usuario de destino
        $request = self::$cliente->get('users/' . $username, [
            'headers' => self::$headers
        ]);

        $response = json_decode($request->getBody(), true);
        $uid = $response['id'];

        try {// Hacer la copia del repositorio
            $request = self::$cliente->post('repos/migrate', [
                'headers' => self::$headers,
                'form_params' => [
                    "auth_username" => config('gitea.user'),
                    "auth_password" => config('gitea.password'),
                    "clone_addr" => 'http://gitlab' . '/' . $repositorio['path_with_namespace'] . '.git',
                    "uid" => $uid,
                    "repo_name" => $destino,
                    "description" => $repositorio['name'],
                    "private" => true,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        if ($request->getStatusCode() == 201) {
            $response = json_decode($request->getBody(), true);

            $data = [
                'id' => $response['id'],
                'name' => $response['name'],
                'description' => $response['description'],
                'http_url_to_repo' => $response['clone_url'],
                'path_with_namespace' => $response['full_name'],
                'web_url' => $response['html_url']
            ];

            return $data;
        } else {
            return $request->getStatusCode() ?: 400;
        }
    }

    public static function borrar()
    {
        self::init();

        $request = self::$cliente->get('repos/search', [
            'headers' => self::$headers,
            'query' => [
                'limit' => 1000
            ]
        ]);
        $response = json_decode($request->getBody(), true);
        $repos = $response['data'];

        $total = 0;
        while (count($repos) > 0) {
            foreach ($repos as $repo) {
                //dump($repo['owner']['username'] . '/' . $repo['name']);
                self::$cliente->delete('repos/' . $repo['owner']['username'] . '/' . $repo['name'], [
                    'headers' => self::$headers
                ]);
                echo '.';
                $total++;
            }

            $request = self::$cliente->get('repos/search', [
                'headers' => self::$headers,
                'query' => [
                    'limit' => 1000
                ]
            ]);
            $response = json_decode($request->getBody(), true);
            $repos = $response['data'];
        }

        return $total;
    }

    public static function user($email, $username, $name)
    {
        self::init();

        $creado = false;

        try {// Hacer la copia del repositorio
            $request = self::$cliente->post('admin/users', [
                'headers' => self::$headers,
                'form_params' => [
                    "email" => $email,
                    "full_name" => $name,
                    "login_name" => $username,
                    "username" => $username,
                    "password" => Str::random(62) . '._',
                    "must_change_password" => true,
                ]
            ]);
            $creado = true;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        return $creado;
    }
}
