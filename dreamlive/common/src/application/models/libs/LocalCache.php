<?php
class LocalCache
{
    public static function set($key, $data, $expire)
    {
        $shmid = shm_attach(ftok("/dev/null", 'a'), 89120);
        
        $value = array(
            'data' => $data,
            'expire' => $expire,
            'time' => time()
        );
        
        $key = crc32($key);
        shm_put_var($shmid, $key, $value);
        
        shm_detach($shmid);
        
        return true;
    }

    public static function get($key)
    {
        $shmid = shm_attach(ftok("/dev/null", 'a'), 89120);
        
        $key = crc32($key);
        
        if (! shm_has_var($shmid, $key)) {
            return false;
        }
        
        $result = shm_get_var($shmid, $key);
        
        shm_detach($shmid);
        
        if (! $result || ! is_array($result)) {
            return false;
        }
        
        if (time() - $result['time'] > $result['expire']) {
            return false;
        }
        
        return $result['data'];
    }

    public static function delete($key)
    {
        $shmid = shm_attach(ftok("/dev/null", 'a'), 89120);
        
        $key = crc32($key);
        shm_remove_var($shmid, $key);
        
        shm_detach($shmid);
    }
}
?>
