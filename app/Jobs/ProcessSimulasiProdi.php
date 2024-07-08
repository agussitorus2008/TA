<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Prodi;
use App\Models\Recommendation;
use DB;

class ProcessSimulasiProdi implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $namaProdi;
    protected $userEmail;

    public function __construct($namaProdi, $userEmail)
    {
        $this->namaProdi = $namaProdi;
        $this->userEmail = $userEmail;
    }

    public function handle()
    {
        set_time_limit(1800);

        // $validate = Validator::make(['prodi' => $this->namaProdi], [
        //     'prodi' => 'required|string|min:5'
        // ], [
        //     'prodi.min' => 'Masukkan minimal 5 karakter untuk nama program studi.'
        // ]);

        // if($validate->fails()){
        //     $recomendation->update([
        //         'data' => json_encode(['error' => "Masukkan minimal 5 karakter"]),
        //     ]);
        //     return;
        // }
        $recomendation = Recommendation::where('email', $this->userEmail)->first();

        if (empty($recomendation)) {
            $recomendation = Recommendation::create([
                'email' => $this->userEmail,
                'data' => json_encode(['data' => ""]),
            ]);
        }

        try {
            $rekomendasiFinal = [];

            $prodiIds = Prodi::where('nama_prodi', 'like', "%{$this->namaProdi}%")->pluck('id_prodi')->toArray();

            if (empty($prodiIds)) {
                $recomendation->update([
                    'data' => json_encode(['error' => "Nama Prodi tidak ada: {$this->namaProdi}"]),
                ]);
                return;
            }

            $nilai = DB::table('view_rekapitulasi_nilai_to')
                ->where('username', $this->userEmail)
                ->value('average_to');

            if (empty($nilai)) {
                $recomendation->update([
                    'data' => json_encode(['error' => "Anda belum memiliki data nilai"]),
                ]);
                return;
            }

            if ($nilai <= 0) {
                $recomendation->update([
                    'data' => json_encode(['error' => "Tidak ada rekomendasi yang cocok untuk kamu"]),
                ]);
                return;
            }

            $checkRekomendasi = DB::table('t_prodi')
                ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
                ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
                ->whereIn('t_prodi.id_prodi', $prodiIds)
                ->select('t_prodi.id_prodi', DB::raw('avg(view_rekapitulasi_nilai_to.average_to) as average_total_nilai'))
                ->groupBy('t_prodi.id_prodi')
                ->get();

            foreach ($checkRekomendasi as $check) {
                if ($nilai >= $check->average_total_nilai) {
                    $rekomendasiFinal[] = $check->id_prodi;
                }
            }

            if (empty($rekomendasiFinal)) {
                $recomendation->update([
                    'data' => json_encode(['error' => "Tidak ada rekomendasi yang cocok untuk kamu"]),
                ]);
                return;
            }

            $prodiRekomendasi = Prodi::whereIn('t_prodi.id_prodi', $rekomendasiFinal)
                ->join('t_ptn', 't_prodi.id_ptn', '=', 't_ptn.id_ptn')
                ->join('kelulusan', 'kelulusan.id_prodi', '=', 't_prodi.id_prodi')
                ->join('view_rekapitulasi_nilai_to', 'view_rekapitulasi_nilai_to.username', '=', 'kelulusan.username')
                ->select(
                    't_prodi.id_prodi',
                    't_prodi.nama_prodi',
                    't_ptn.nama_ptn',
                    't_ptn.nama_singkat',
                    DB::raw('AVG(view_rekapitulasi_nilai_to.average_to) as average_total_nilai')
                )
                ->groupBy(
                    't_prodi.id_prodi',
                    't_prodi.nama_prodi',
                    't_ptn.nama_ptn',
                    't_ptn.nama_singkat'
                )
                ->orderByDesc('average_total_nilai')
                ->get();

            // Store the results in the database
            $recomendation->update([
                'data' => json_encode($prodiRekomendasi),
            ]);

        } catch (\Exception $exception) {
            $recomendation->update([
                'data' => json_encode(['error' => "Error processing dataset: " . $exception->getMessage()]),
            ]);
        }
    }
}
