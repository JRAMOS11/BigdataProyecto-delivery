<?php
 
namespace Controllers;
 
class Index extends PublicController
{
    public function run(): void
    {
        $isLogged = \Utilities\Security::isLogged();
        $data = [
            'isLogged' => $isLogged,
            'carouselImages' => [
                [
                    'image_url' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?auto=format&fit=crop&w=1200&q=80',
                    'image_title' => 'Pizza artesanal',
                    'image_desc' => 'Masa crujiente, queso fundido y albahaca fresca.',
                ],
                [
                    'image_url' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=1200&q=80',
                    'image_title' => 'Hamburguesa clasica',
                    'image_desc' => 'Carne jugosa, vegetales frescos y pan dorado.',
                ],
                [
                    'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=80',
                    'image_title' => 'Ensalada fresca',
                    'image_desc' => 'Ingredientes ligeros para acompanar tu pedido.',
                ],
            ],
        ];
 
        if ($isLogged) {
            $user = \Utilities\Security::getUser();
            $data['userName'] = $user['userName'] ?? '';
            $data['userRol']  = $user['userRol']  ?? '';
        }
 
        \Views\Renderer::render('index', $data);
    }
}
 
