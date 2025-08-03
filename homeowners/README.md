# Homewowners Tech Exercise
At first glance, I was really confident, then I started looking at the discrepancies in the data and was 
a little more tricky than had first thought. Makes life much easier when you have more control over the data you are putting into the app 🤣
I'd make some improvements if I had more time, will leave my thoughts below.

Wrote a test to cover a cover some of the different circumstances in the service doing the heavy lifting, would write feature test on the controller also but ran out of time.

### Improvements I would make
* **Allow for extra columns** - Has scope to grow so would want to be able to handle more columns for data
* **Render results more dynamically** - Could use the array keys of the person to serve as the table headers, that way if the person grows, the table can follow it 
with less work to add extra headers/columns.
* **Tighter error handling** - For the purposes of the exercise, what i have written does the job, but in real life, would want more graceful error handling to account for the multiple problems could be introduced by feeding an application data that it doesnt have context on.
  * Would want to send the user back to upload page with an error message if they try to upload wrong file/or the code cant read the file rather than just log.
