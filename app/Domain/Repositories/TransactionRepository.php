<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Transaction;
use App\Domain\Contracts\TransactionInterface;
use App\Domain\Contracts\Crudable;


/**
 * Class TransactionRepository
 * @package App\Domain\Repositories
 */
class TransactionRepository extends AbstractRepository implements TransactionInterface, Crudable
{

    /**
     * @var Transaction
     */
    protected $model;

    /**
     * TransactionRepository constructor.
     * @param Transaction $contact
     */
    public function __construct(Transaction $contact)
    {
        $this->model = $contact;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * @param int $limit
     * @param int $page
     * @param array $column
     * @param string $field
     * @param string $search
     * @return \Illuminate\Pagination\Paginator
     */
    public function paginate($limit = 10, $page = 1, array $column = ['*'], $field, $search = '')
    {
        // query to aql

        $transactions = $this->model
            ->join('books', 'transactions.book_id', '=', 'books.id')
            ->where(function ($query) use ($search) {
                $query->where('books.judul', 'like', '%' . $search . '%');
            })
            ->orderBy('transactions.created_at','asc')
            ->paginate($limit);

        return $transactions;
    }

    /**
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(array $data)
    {
        // execute sql insert
        return parent::create([
            'book_id'    => e($data['book_id']),
            'user_id'   => e($data['user_id']),
            'petugas' => e($data['petugas']),
            'status'   => e($data['status']),
            'expired_at'   =>  \Carbon\Carbon::now()->addDays(7),
        ]);

    }

    /**
     * @param $id
     * @param array $data
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id, array $data)
    {
        return parent::update($id, [
            'book_id'    => e($data['book_id']),
            'user_id'   => e($data['user_id']),
            'petugas' => e($data['petugas']),
            'status'   => e($data['status']),            
        ]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete($id)
    {
        return parent::delete($id);
    }


    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function findById($id, array $columns = ['*'])
    {
        return parent::find($id, $columns);
    }

}