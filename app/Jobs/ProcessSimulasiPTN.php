<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Prodi;
use App\Models\Recommendation;
use DB;

class ProcessSimulasiPTN implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $idPTN;
    protected $userEmail;

    public function __construct($idPTN, $userEmail)
    {
        $this->idPTN = $idPTN;
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(1800);

        $recomendation = Recommendation::where('email', $this->userEmail)->first();

        if (empty($recomendation)) {
            $recomendation = Recommendation::create([
                'email' => $this->userEmail,
                'data' => json_encode(['data' => ""]),
            ]);
        }

        try {
            $rekomendasiFinal = [];

            // Fetch prodi IDs related to the given PTN ID
            $prodiIds = Prodi::where('id_ptn', $this->idPTN)->pluck('id_prodi')->toArray();

            // Get the average score of the authenticated user
            $nilai = DB::table('view_rekapitulasi_nilai_to')
                ->where('username', $this->userEmail)
                ->value('average_to');
            // $nilai = 86.45447143;

            // If the user has no score data, return an error
            
            if (is_null($nilai)) {
                $recomendation->update([
                    'data' => json_encode(['error' => "Anda belum memiliki data nilai"]),
                ]);
                return;
            }

            // If the score is zero or negative, return an error
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
                ->select('t_prodi.id_prodi', DB::raw('AVG(view_rekapitulasi_nilai_to.average_to) as average_total_nilai'))
                ->groupBy('t_prodi.id_prodi')
                ->get();

            foreach($checkRekomendasi as $check) {
                if($nilai >= $check->average_total_nilai) { 
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
                ->groupBy('t_prodi.id_prodi', 't_prodi.nama_prodi', 't_ptn.nama_ptn', 't_ptn.nama_singkat')
                ->orderByDesc('average_total_nilai')
                ->get();

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
