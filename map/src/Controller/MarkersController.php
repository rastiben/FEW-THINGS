<?php

namespace App\Controller;

use Cake\Controller\Controller;

class MarkersController extends AppController
{
    
    public function index(){
        $markers = $this->Markers->find('all');
        
        $this->set([
            'markers' => $markers,
            '_serialize' => ['markers']
        ]);
    }
    
    public function display(){
        $this->render('index');
    }
    
    public function view($id){
        $marker = $this->Markers->get($id);
        
        $this->set([
            'marker' => $marker,
            '_serialize' => ['marker']
        ]);
    }
    
    public function add()
    {
        //var_dump($this->request->getData());
        //die();
        $marker = $this->Markers->newEntity($this->request->getData());
        if ($this->Markers->save($marker)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            'marker' => $marker,
            '_serialize' => ['message', 'marker']
        ]);
    }

    public function edit($id)
    {
        $marker = $this->Markers->get($id);
        if ($this->request->is(['post', 'put'])) {
            $marker = $this->Markers->patchEntity($marker, $this->request->getData());
            if ($this->Markers->save($marker)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }

    public function delete($id)
    {
        $marker = $this->Markers->get($id);
        $message = 'Deleted';
        if (!$this->Markers->delete($marker)) {
            $message = 'Error';
        }
        $this->set([
            'message' => $message,
            '_serialize' => ['message']
        ]);
    }
    
}