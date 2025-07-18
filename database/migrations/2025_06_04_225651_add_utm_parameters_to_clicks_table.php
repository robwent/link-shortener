<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->string('utm_source')->nullable()->after('referer');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');

            // Add index for campaign analytics
            $table->index(['utm_source', 'utm_medium', 'utm_campaign']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clicks', function (Blueprint $table) {
            $table->dropIndex(['utm_source', 'utm_medium', 'utm_campaign']);
            $table->dropColumn([
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_term',
                'utm_content',
            ]);
        });
    }
};
