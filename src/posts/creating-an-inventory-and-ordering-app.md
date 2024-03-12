---
title: Creating an Inventory and Ordering App
variables: 
  date: &date 2024-03-12 00:00:00 -5:00
date: *date
history: [*date]
labels: ['webapp']
---

I finally deployed my second version of my Starbucks webapp! I've created a handy website that my fellow partners at my workplace can use to take inventory of our product and know what to order every two days.

<!-- excerpt -->

My initial app used the native input of our phones to update input fields but that was a lot of opening and closing the on-screen keyboard. It also only used session storage to hold the current inventory data and if the phone screen went to sleep when we were pulled away from taking inventory (as we often are), it sometimes-but-not-always would reset the entire inventory process. So frustrating!

Now I'm using local storage that stays in the browser's memory until we complete an entire ordering process or manually clear our browser's cookies and memory.

I just made a whole list of posssible improvements that I'd like to tackle bit by bit. A major weakness of mine is biting off more than I can chew codingwise and not planning out my goals. I hope this is good practice to do things well from start to finish, with constant integration of this new webapp version 2!