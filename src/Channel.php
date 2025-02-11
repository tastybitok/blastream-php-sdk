<?php
namespace Blastream;

class Channel extends Instance {
    
    use Document;
    use Collaborators;
    use Scenes;
    
    protected $_is_channel = true;
    
    public function setSlug($slug) {
        $this->_slug = $slug;
    }
    
    public function setAccessRule($privacy, $params = false) {
        if($params == false)
            $params = new \stdClass();
        if($privacy == 'PRIVATE')
            $privacy = 2;
        if($privacy == 'PUBLIC')
            $privacy = 0;
        return $this->put('/channel/rule', [
            'body' => [
                'privacy' => $privacy,
                'data' => $params
            ]
        ]);
    }
    
    public function createOrRefreshSpeakersToken() {
        return $this->put('/channel/speakers-token');
    }
    
    public function removeSpeakersToken() {
        return $this->delete('/channel/speakers-token');
    }
    
    public function getSpeakersToken() {
        return $this->get('/channel/speakers-token');
    }
    
    public function getReplays() {
        return $this->get('/channel/videos');
    }
    
    public function updateSettings($params) {
        return $this->post('/channel/settings', [
            'body' => [
                'data' => $params
            ]
        ]);
    }
    
    public function updateChatSettings($params) {
        return $this->post('/chat/settings', [
            'body' => [
                'data' => $params
            ]
        ]);
    }
    
    public function updateSubscription($plan, $billing) {
        return $this->post('/channel/subscription', [
            'body' => [
                'plan' => $plan,
                'billing' => $billing
            ]
        ]);
    }
    
    public function setCustom($params = []) {
        if($this->isV1()) {
            if(!isset($prams['css']))
                $prams['css'] = '';
            if(!isset($prams['js']))
                $prams['js'] = '';
        }
        return $this->post('/channel/custom', [
            'body' => [
                'data' => $params
            ]
        ]);
    }
    
    public function removeCustom($params = []) {
        return $this->delete('/channel/custom');
    }
    
    public function disconnectAll() {
        return $this->post('/channel/disconnectall');
    }
    
    public function sendMessage($params) {
        $this->_is_channel = false;
        $result = $this->post('/api/msg', [
            'body' => [
                'msg' => $params['msg'],
                'username' => $params['username'],
                'slug' => $this->_slug
            ]
        ]);
        $this->_is_channel = true;
        return $result;
    }
    
    public function remove() {
        $this->_is_channel = false;
        $result = $this->delete('/space/' . $this->_slug);
        $this->_is_channel = true;
        return $result;
    }
}
?>
