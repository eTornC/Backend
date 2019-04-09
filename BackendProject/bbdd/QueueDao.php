<?php

namespace eTorn\Bbdd;

use eTorn\Models\Queue;

class QueueDao {

    public function findAll()
	{
        return Queue::all();
    }

    public function findById($id)
	{
        return Queue::find($id);
    }

    public function findByProperty($property, $value)
	{
        return Queue::where($property, $value)->get();
    }

    public function save(Queue $queue)
	{
        return $queue->save();
    }

    public function delete(Queue $queue)
	{
        return $queue->delete();
    }

    public function findByIdStore($idStore) {
        return $this->findByProperty("id_store",  $idStore);
    }

    public function getNormalQueue($idStore) {

        $query = "SELECT * FROM QUEUE WHERE ID_STORE = $idStore AND QUEUE_TYPE LIKE 'NORMAL'";

        $result = parent::query($query);

        if ($row = $result->fetch_assoc()) {

            $queue = new Queue();
            $queue->setId($row['ID']);
            $queue->setType($row['QUEUE_TYPE']);
            $queue->setPriority($row['PRIORITY']);
            $queue->setIdStore($row['ID_STORE']);

            return $queue;
        }

        return null;
    }

    public function getVipQueue($idStore)
    {
        return Queue::where('id_store', $idStore)->where('type', 'VIP')->first();
    }
}