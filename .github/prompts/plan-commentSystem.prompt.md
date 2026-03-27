## Plan: Dodanie Systemu Komentarzy

Wdrożenie funkcji komentowania postów na blogu wraz z obsługą relacji bazodanowych i walidacją.

**Steps**
1. **Model i Baza Danych**: Użycie komendy `php artisan make:model Comment -mfs` do utworzenia modelu `Comment`, migracji, fabryki oraz seedera.
2. **Migracja**: Dodanie pól do tabeli `comments`: `post_id` (foreignId `cascadeOnDelete`), `author` (string), `email` (string) oraz `content` (text).
3. **Relacje i Pola (Fillable)**:
   - W `app/Models/Post.php` dodanie relacji `comments()` (hasMany).
   - W `app/Models/Comment.php` dodanie relacji `post()` (belongsTo) oraz odpowiednich zabezpieczeń przez `$fillable`.
4. **Walidacja Formularza**: Wygenerowanie Requestu (Form Request) do walidacji danych przesyłanych w formularzu `php artisan make:request StoreCommentRequest`.
5. **Kontroler i Routing**:
   - Utworzenie kontrolera do obsługi żądań związanych z komentarzami np. `PostCommentController`.
   - Rejestracja trasy POST w zagnieżdżonej ścieżce (np. `/posts/{post:slug}/comments`) w `routes/web.php`.
6. **Widoki**: Modyfikacja widoku wyświetlania posta (`resources/views/posts/show.blade.php`), dodając sekcję do wpisywania komentarza oraz listę już opublikowanych komentarzy powiązanych z danym postem.
7. **Testy Pest**: Wygenerowanie i napisanie testów integracyjnych w Pest (komenda `php artisan make:test PostCommentTest --pest`), które weryfikują m.in:  
   - Poprawność utworzenia komentarza.
   - Walidację pól (wymagane autor, email i treść).
   - Poprawne przypisanie komentarza do odpowiedniego postu.

**Relevant files**
- `app/Models/Post.php` — Obsługa relacji `hasMany`.
- `app/Models/Comment.php` (nowy) — Model komentarza powiązanego z postami z użyciem `$fillable`.
- `routes/web.php` — Nowa ścieżka dla API komentarzy.
- `resources/views/posts/show.blade.php` — Formularz komentowania i lista komentarzy.
- `app/Http/Controllers/PostCommentController.php` (nowy) — Kontroler dla obsługi logiki zapisania komentarza.
- `app/Http/Requests/StoreCommentRequest.php` (nowy) — Walidacja przychodzących danych post.
- `database/migrations/..._create_comments_table.php` (nowy) — Tabela przechowująca dane.
- `tests/Feature/PostCommentTest.php` (nowy) — Testy Pest sprawdzające wymogi zadania.

**Verification**
1. Uruchomienie komendy `php artisan migrate`, aby zaktualizować bazę.
2. Odpalenie przygotowanych testów za pomocą `php artisan test --compact --filter=PostCommentTest`.
3. Ręczne przetestowanie wprowadzania komentarzy w interfejsie przeglądarki.

**Decisions**
- Brak pre-moderacji. Komentarze będą widoczne od razu po dodaniu. Jeśli zajdzie potrzeba, możemy dodać pole statusu zatwierdzania, lecz domyślnie to wyłączymy dla prostoty MVP.

**Further Considerations**
1. **Ochrona Spamowa:** Czy chciałbyś na tym etapie dodać ochronę przed spamem (np. pole *honeypot* lub Google reCAPTCHA)?
2. **Sortowanie Komentarzy:** Rozumiem, że komentarze powinny wyświetlać się od najstarszych do najnowszych (domyślnie)?
