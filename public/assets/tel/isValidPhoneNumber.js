function isValidPhoneNumber(phone)
{
    var filter = /^[\d-]*$/; //only allow digits and hyphens - custom regex
   // console.log(isValidPhoneNumber("52266197"));
    if (filter.test(phone)) {
        return true;
    } else {
        return false;
    }
}
