# UrlKeeper
A simple URL store written in PHP using SQLite3.

This small application was and is an experiment. How much longer would it
take to build a simple CRUD application without any given framework?

It took some time to build the basics and there are still some missing basic
features, e. g.:

- Validation (and messages)
- User Management
- Search
- More test coverage
- Better layout (the current one is a compromise between mobile and desktop and non-responsive)
- There is some confusion between Models and Services

After watching [Stefan Priebsch - SOLID MVC](https://vimeo.com/148989192) I noticed my way of
having seperate Action objects for each action can go into the right direction, but I missed the
point of explicit dependencies. Maybe that's a good thing for the next refactoring session.

If you want to have a look, you can find a demo of the application [here](https://florianhansen.net/urlkeeper/).
