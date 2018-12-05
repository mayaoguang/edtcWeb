<?php
namespace App\Console\Commands;
 
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Service\VoteService;
use App\Service\AccountService;
use App\Service\NodeService;
use Illuminate\Support\Facades\DB;
 
class CancelVoteCommand extends Command {
    /**
     * @var string
     * The console command name.
     * */
    protected $name = 'cancelVote';
 
    /**
     * @var string
     * The console command description.
     */
    protected $description = 'cancelVote time';
 
    /**
     * Execute the console command.
     * @return mixed
     */
    public function handle() {
        $local_time = time();
        Log::info('start cancelVote:'.$local_time);
        $vote_data = VoteService::getVoteDataByStatus(2);
        foreach ($vote_data as $vote) {
            if($vote['unlock_time'] < $local_time){
                DB::beginTransaction();
                try{
                    $updata['vote_status'] = 3;
                    VoteService::updataVoteData($vote['order_number'], $updata);

                    $record_data['operation_type'] = 10;
                    $record_data['user_id'] = $vote['user_id'];
                    $record_data['number'] = $vote['vote_number'];
                    $record_data['status'] = 1;
                    $record_data['operation_id'] = $vote['order_number'];
                    AccountService::newRecord($record_data);

                    $amount = AccountService::getAmountById($vote['user_id']);
                    $amount_chang = AccountService::setAmountById($vote['user_id'], $amount['amount'] + $vote['vote_number']);
                    $lock_res = AccountService::setLockAmountById($vote['user_id'], $amount['lock_amount'] - $vote['vote_number']);

                    $vote_account = NodeService::getVoteAccountById($vote['node_id']);
                    $account_change = NodeService::setVoteAccount($vote['node_id'], $vote_account - $vote['vote_number']);
                    DB::commit();
                    Log::info('cancelVote success.time now:'.$local_time);
                    Log::info($vote);
                }catch(Exception $e){
                    DB::rollBack();
                    Log::info('error updata to mysql error:'.$e);
                }
            }else{
                Log::info('error: '.$vote['unlock_time'].'> nowtime ='.$local_time);
            }
        }
        Log::info('end cancelVote');
    }
}