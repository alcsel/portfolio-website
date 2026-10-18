# WebProject

Personal portfolio site of **Selim Calici** (student number B251210378), a Computer Engineering
student at Sakarya University. The site is plain HTML, CSS and vanilla JavaScript on top of a
Bootstrap 5 theme, with two small PHP scripts that handle the contact form and a demo sign-in.

The site is entirely static apart from those two PHP endpoints, so it can be hosted on any web
server with PHP — no database and no build step.

## Pages

| Page | File | Contents |
| --- | --- | --- |
| About | `index.html` | Landing page: introduction, key figures, hobbies and featured projects |
| CV | `cv.html` | Education, projects, technical skills and interests |
| My City | `city.html` | Sakarya: photo slider, key figures, places to visit, history and economy |
| My Team | `team.html` | Sakaryaspor: club history, milestones, stadium, squad structure |
| Interests | `interests.html` | Live book search against the Open Library API |
| Contact | `contact.html` | Contact form with client-side validation and server-side handling |
| Sign in | `login.html` | Demo sign-in form |

Two PHP scripts complete the site:

| Script | Purpose |
| --- | --- |
| `form_handler.php` | Receives the contact form by POST and renders the submitted values |
| `login.php` | Verifies the credentials configured on the server and shows a welcome panel |

## Running locally

Any static server works for the HTML pages; PHP is only needed for the form handler and the
sign-in script.

```bash
php -S localhost:8000
```

Then open <http://localhost:8000/>. The PHP built-in server serves the HTML, CSS and images as
well, so nothing else is required. PHP 8.0 or newer is recommended.

## Sign-in configuration

The sign-in script never contains credentials. It reads them from the environment and refuses
the login when they are missing:

- `PORTFOLIO_EMAIL` — the email address accepted by the form
- `PORTFOLIO_PASSWORD_HASH` — a password hash produced by `password_hash()`

Values are looked up in `$_ENV`, then `$_SERVER`, then `getenv()`.

### 1. Generate a password hash

```bash
php -r "echo password_hash('your-new-password', PASSWORD_DEFAULT), PHP_EOL;"
```

Copy the printed hash — the whole line including the `$2y$` prefix.

### 2. Provide the values

Either export them in the shell that starts PHP:

```bash
PORTFOLIO_EMAIL=b000000000@sakarya.edu.tr
PORTFOLIO_PASSWORD_HASH='$2y$12$...'
```

or copy `config.example.php` to `config.php` and fill in the two `putenv()` calls. `config.php`
is listed in `.gitignore`, so real credentials can never be committed; only the placeholder
example file belongs in the repository.

When neither source is configured, `login.php` answers with HTTP 503 and a "Sign-in is not
configured" panel instead of accepting a login. Failed logins redirect to
`login.html?error=invalid`, empty submissions to `login.html?error=empty`, and the page shows the
matching message.

## How the contact form works

`contact.html` posts nine fields to `form_handler.php`:

`first_name`, `last_name`, `email`, `phone`, `subject`, `gender`, `interests[]`, `message`,
`consent`

The form can be validated in two ways: the first button runs the native JavaScript validator, the
second one runs the Vue.js validator. Both collect the same values and post them to the same
endpoint. All request values are escaped where they are printed, so the handler can safely render
the submission in an HTML table. `form_handler.php` only accepts `POST` requests; anything else
is redirected back to the form.

## Project layout

```
WebProject/
├── index.html            About / landing page
├── cv.html               Curriculum vitae
├── city.html             Sakarya
├── team.html             Sakaryaspor
├── interests.html        Book discovery
├── contact.html          Contact form
├── login.html            Sign-in form
├── form_handler.php      Contact form handler
├── login.php             Sign-in handler
├── config.example.php    Template for local credentials
├── assets/css/style.css  The single stylesheet
├── img/                  Photographs used by the gallery
├── .editorconfig
├── .gitignore
├── LICENSE
└── README.md
```

The gallery photographs are stored at a maximum edge length of 1600 px and re-encoded as JPEG so
that each file stays in the low hundreds of kilobytes.

## Coursework

Part of the content — the CV, the "My City" and the "My Team" pages — started life as coursework
for the Web Technologies course at Sakarya University. The rest of the material is personal.

## License

Released under the MIT License. See [LICENSE](LICENSE). Copyright (c) 2026 Selim Calici.
