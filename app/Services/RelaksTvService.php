<?php

namespace App\Services;

use App\Models\RelaksTv;

class RelaksTvService
{
    /**
     * Get all RelaksTvs.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return RelaksTv::with('radioStation')->get();
    }

    /**
     * Find a RelaksTv by ID.
     *
     * @param  int  $id
     * @return \App\Models\RelaksTv
     */
    public function findById($id)
    {
        return RelaksTv::with('radioStation')->findOrFail($id);
    }

    /**
     * Create a new RelaksTv.
     *
     * @param  array  $data
     * @return \App\Models\RelaksTv
     */
    public function create(array $data)
    {
        return RelaksTv::create($data);
    }

    /**
     * Update a RelaksTv.
     *
     * @param  int  $id
     * @param  array  $data
     * @return \App\Models\RelaksTv
     */
    public function update($id, array $data)
    {
        $relaksTv = $this->findById($id);
        $relaksTv->update($data);
        return $relaksTv;
    }

    /**
     * Delete a RelaksTv.
     *
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        $relaksTv = $this->findById($id);
        $relaksTv->delete();
    }
}
