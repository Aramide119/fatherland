<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('text')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->timestamps();
        });

        // Update existing posts with slugs based on text
        $posts = \App\Models\Post::all();
        foreach ($posts as $post) {
            $slug = $post->slug = $post->text ? Str::slug($post->text) : $this->generateUniqueDefaultSlug();
            $post->slug = Str::limit($slug, 15); // Trim the slug to the column's maximum length
            $post->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }

    private function generateUniqueDefaultSlug()
    {
        $baseSlug = 'flpost';
        $uniquePart = Str::uuid()->toString(); 
        return Str::slug($baseSlug . '-' . $uniquePart);
    }
}
