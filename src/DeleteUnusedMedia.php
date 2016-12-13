<?php

namespace Microit\LaravelAdminBaseStandalone;

use App\User;
use DCN\RBAC\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class DeleteUnusedMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:delete-unused-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unused media';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $media = Medium::all();
        $table_headers = ['Object', 'ID', 'Insert time', 'File'];
        $table_info = [];
        foreach ($media as $medium) {
            $model = $medium->mediable_type;
            $object = $model::whereId($medium->mediable_id)->first();
            if (is_null($object)) {
                $table_info[] = [
                    $medium->mediable_type,
                    $medium->mediable_id,
                    $medium->created_at,
                    file_exists($medium->getPath()) ? $medium->getPath() : '---',
                ];
            }
        }

        $this->table($table_headers, $table_info);

        if ($this->confirm("Delete media?")) {
            foreach ($media as $medium) {
                $model = $medium->mediable_type;
                $object = $model::whereId($medium->mediable_id)->first();
                if (is_null($object)) {
                    if (file_exists($medium->getPath())) {
                        unlink($medium->getPath());
                        $this->info("Delete file: ".$medium->getPath());
                    }
                    $this->info("Delete media id: ".$medium->id);
                    $medium->delete();
                }
            }
        }

        return;
    }
}
