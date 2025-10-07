<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarEventUserCompensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_event_user_compensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('calendar_event_user_status_id')->constrained()->cascadeOnDelete()->name('calevent_user_comp_cale_user_status_id_fk');;
            $table->foreignId('calendar_event_id')->nullable()->constrained()->nullOnDelete(); // Useful for easier queries
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();  // Useful for easier queries
            $table->enum('status', ['attended', 'no-show', 'canceled'])->nullable();
            $table->boolean('paid')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_event_user_compensations');
    }
}
