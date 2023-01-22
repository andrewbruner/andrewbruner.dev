---
postTitle: Websites with Integrity
published: '22 August 2021'
layout: post.njk
permalink: '/{{ postTitle | slugify }}/'
labels: ['html']
---

I was recently listening to a podcast episode of [Syntax](https://syntax.fm/show/379/hasty-treat-the-weird-and-wonderful-less-than-link-greater-than-tag) where the subject of HTML `<link>` tags was discussed. One of the many reasons we may use link tags is to *link* our page to another document, like a CSS stylesheet, a web font, or a file from a content delivery network (CDN). The latter may look something like this:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css"
rel="stylesheet"
integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We"
crossorigin="anonymous">
```

That third line is an interesting one. What does the attribute *integrity* mean and what's with all those letters and numbers?

That, my friends, is called **subresource integrity**, and it is an amazing security feature. It verifies that the resource you are importing into your site is actually the one you think it is. In my [previous post](https://andrewbruner.dev/building-a-simple-javascript-blockchain), I mentioned the concept of a secure hashing algorithm (SHA): a cryptographic "hashing" function that takes a string of any length and converts it into a fixed-length "hash value". It only works one way and is therefore secure for passwords or anything you don't want someone to guess or know. It's also great to know if that string (or an entire document in our case) has been changed, for good or bad.

Say a popular CDN is hacked and code is inserted that forces thousands of other machines that access it to mine cryptocurrencies for the malicious party. A simple `integrity` attribute can enable the browser using that resource to know if has been changed. It uses a specific subresource integrity (SRI) hashing algorithm to check that the resource being imported matches the predefined hash value and if it doesn't, the browser declines to import it.

This long, weird-looking line of code has befuddled me in the past, but I hope that if it's looked foreign to you before, it's a little plainer now. Security on today's world wide web is crucial, but even with all the dishonest players in the game, it's nice to know we can still have a little *integrity*.
