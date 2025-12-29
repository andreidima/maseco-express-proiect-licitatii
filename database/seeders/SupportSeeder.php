<?php

namespace Database\Seeders;

use App\Models\SupportThread;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SupportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('ro_RO');

        $adminUser = User::whereIn('role', ['SuperAdmin', 'Admin'])->first();

        if (! $adminUser) {
            $adminUser = User::updateOrCreate(
                ['email' => 'admin-suport@licitatii.maseco.ro'],
                [
                    'name' => 'Admin Suport',
                    'password' => Hash::make('Suport@2025'),
                    'activ' => true,
                    'role' => 'Admin',
                    'telefon' => '0733001234',
                ]
            );
        }

        $companyNames = [
            'TransMarfă Expres',
            'Nord Logistic SRL',
            'Delta Freight Services',
            'Orizont Transport',
            'Eficient Carrier',
            'Azur Cargo',
            'RapidLift Group',
            'Callatis Logistics',
            'Constanța Tranzit',
            'Metropolitan Transport',
            'Est-Vest Freight',
            'Atlas Road Services',
            'Prahova Cargo Fleet',
            'Siret Transport Solutions',
            'MoldCargo Expres',
            'Oltenia Logistic Partners',
            'Carpathian Haulers',
            'Parisbei Cargo',
            'Bucovina Freight Lines',
            'Dunărea Transport',
            'Somes Highway',
            'Black Sea Express',
            'AeroCargo Services',
            'Brăila Cargo Hub',
            'Craiova Freight Team',
            'IronDoor Logistics',
            'Praxis Transport',
            'Sindicate Cargo',
            'Orizont Freight',
            'InterRuta Express',
        ];

        $subjectTemplates = [
            'Clarificare ofertă pentru licitația :auction',
            'Documente care lipsesc pentru lotul :auction',
            'Confirmare plată pentru transportul din licitația :auction',
            'Actualizare traseu licitație :auction',
            'Probleme cu notificările pentru :auction',
            'Solicitare ajustare tarif pentru lotul :auction',
            'Validare documente fiscale pentru licitația :auction',
            'Prioritizare ofertă la :auction',
            'Divergență între tarif și contract în :auction',
            'Întârziere validare ofertă în portal pentru :auction',
        ];

        $participantMessages = [
            'Bună ziua, am încărcat oferta pentru licitația :auction, dar sistemul afișează eroare că nu sunt atașate documentele de transport. Ce trebuie să fac pentru a debloca lotul?',
            'După ce am trimis oferta pentru :auction nu primesc confirmare și statusul rămâne „în așteptare”. Aveți o estimare pentru validare?',
            'Documentele pentru licitația :auction au rămas în stadiul „fără confirmare”. Am retrimis factura și CMR-ul. Vă rog să verificați la departamentul financiar.',
            'Trebuie să modific adresa de încărcare pentru :auction, dar nu pot edita oferta. Mă puteți ghida cât mai repede?',
            'Pentru :auction apare plata în avans, dar în aplicație nu pot încărca dovada plății. Unde se trimite acel document?',
            'Am nevoie să discut cu cineva înainte să finalizez oferta pentru :auction. Aveți un contact disponibil pe chat?',
            'Tariful final afișat în :auction nu corespunde cu cel din propunerea depusă. Este posibil să fie o eroare în calcul?',
            'Lotul dedicat licitației :auction apare ca blocat, dar în sistem este încă activ. Puteți confirma dacă este liber?',
            'Apare eroarea „nu pot procesa linia de tarif în licitația :auction” deși toate câmpurile sunt completate. Ce parametru mai trebuie?',
            'Am încărcat documentele solicitate pentru :auction, dar platforma cere încă un PDF de confirmare. Ce anume lipsește?',
        ];

        $adminMessages = [
            'Am verificat licitația :auction și documentele sunt încă în procesare; am notificat echipa de conformitate și ar trebui să primiți un răspuns azi.',
            'Pentru :auction confirm că factura a ajuns, reîncărcați doar dovada de plată în secțiunea dedicată și vom actualiza statusul.',
            'Actualizarea adresei pentru :auction este permisă, v-am trimis un link de confirmare în emailul asociat contului.',
            'Se pare că lotul din :auction este deja rezervat, dar vă pot recomanda un alt lot similar care rămâne deschis.',
            'Am resetat procesul de ofertare pentru :auction și acum puteți modifica tariful; salvați după ajustare ca să rămână activ.',
            'Eroarea din :auction a apărut din cauza unei discrepanțe în tariful per kilometru; am corectat manual valoarea corectă.',
            'Statusul pentru :auction este „în analiză”; revenim cu confirmarea finală în momentul în care echipa de achiziții termină verificarea.',
            'Am cerut confirmarea financiară pentru :auction și v-am trecut statusul la „în procesare”. Citiți notificarea trimisă de sistem.',
            'Am înregistrat solicitarea dvs. pentru :auction; trimiteți în continuare dovada semnată prin secțiunea „Atașamente”.',
            'Mulțumim pentru sesizare. Echipa de suport pentru :auction va răspunde în următoarele 2 ore.',
        ];

        $followUps = [
            'Mulțumesc, am primit notificarea pentru :auction. Mai revin cu încă o întrebare legată de termene.',
            'Perfect, revin după ce finalizez ajustarea tarifului pentru :auction.',
            'Am primit confirmarea și închid acum cererea legată de :auction.',
            'Înțeleg, reiau oferta când se aprobă actualizarea pentru :auction.',
            'Vă mulțumesc, aștept actualizarea oficială pentru :auction.',
        ];

        $problemSummaries = [
            'Documentele necesare pentru licitația :auction nu sunt validate de departament.',
            'Platforma nu acceptă factura proformă încărcată pentru :auction.',
            'Tariful final afișat nu coincide cu oferta mea din :auction.',
            'Lotul din :auction apare cu status „anulat” deși este activ.',
            'Nu pot atașa dovada de asigurare pentru transportul din :auction.',
        ];

        $categories = ['Documente', 'Platformă', 'Plată', 'Tarif', 'Notificări'];
        $severities = ['Ușoară', 'Mediu', 'Urgent'];
        $statuses = ['open', 'pending', 'resolved'];

        $participants = [];

        foreach ($companyNames as $index => $company) {
            $email = sprintf('participant%02d@licitatii.maseco.ro', $index + 1);

            $participants[] = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $company,
                    'password' => Hash::make('Licitatii@2025'),
                    'activ' => true,
                    'role' => 'Participant licitatii',
                    'telefon' => $faker->numerify('07########'),
                ]
            );
        }

        foreach ($participants as $participant) {
            if ($participant->supportThreads()->exists()) {
                continue;
            }

            $threadCount = rand(5, 10);

            for ($i = 0; $i < $threadCount; $i++) {
                $auctionCode = 'LC-' . rand(1000, 9999);
                $subject = str_replace(':auction', $auctionCode, Arr::random($subjectTemplates));
                $type = Arr::random(['chat', 'chat', 'problem']);
                $status = Arr::random($statuses);
                $category = $type === 'problem' ? Arr::random($categories) : null;
                $severity = $type === 'problem' ? Arr::random($severities) : null;
                $summary = $type === 'problem' ? str_replace(':auction', $auctionCode, Arr::random($problemSummaries)) : null;

                $thread = SupportThread::create([
                    'participant_user_id' => $participant->id,
                    'subject' => $subject,
                    'type' => $type,
                    'status' => $status,
                    'problem_category' => $category,
                    'problem_severity' => $severity,
                    'problem_summary' => $summary,
                    'last_activity_at' => now(),
                ]);

                $messages = [];
                $timeCursor = Carbon::now()->subDays(rand(0, 10))->subMinutes(rand(0, 300));

                $participantBody = str_replace(':auction', $auctionCode, Arr::random($participantMessages));
                $messages[] = [
                    'sender_id' => $participant->id,
                    'sender_role' => 'participant',
                    'body' => $participantBody,
                    'created_at' => $timeCursor,
                    'updated_at' => $timeCursor,
                ];

                if ($adminUser) {
                    $timeCursor = $timeCursor->copy()->addMinutes(rand(5, 45));
                    $messages[] = [
                        'sender_id' => $adminUser->id,
                        'sender_role' => 'admin',
                        'body' => str_replace(':auction', $auctionCode, Arr::random($adminMessages)),
                        'created_at' => $timeCursor,
                        'updated_at' => $timeCursor,
                    ];
                }

                if ($adminUser && rand(0, 1)) {
                    $timeCursor = $timeCursor->copy()->addMinutes(rand(5, 25));
                    $messages[] = [
                        'sender_id' => $participant->id,
                        'sender_role' => 'participant',
                        'body' => str_replace(':auction', $auctionCode, Arr::random($followUps)),
                        'created_at' => $timeCursor,
                        'updated_at' => $timeCursor,
                    ];
                }

                $thread->messages()->createMany($messages);

                $thread->update([
                    'last_activity_at' => $timeCursor,
                    'admin_user_id' => $adminUser->id,
                ]);
            }
        }
    }
}
