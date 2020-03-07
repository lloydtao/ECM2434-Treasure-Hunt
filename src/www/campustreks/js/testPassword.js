/**
 * Tests if password is secure by checking length and NBP list
 * @param password
 * @return boolean true if strong password, else false
 */

function testPassword(password) {
    if (password.length < 8) {
        return false;
    }
    NBP.init("mostcommon_100000", "collections/", true);
    //check against the top 1000000 used passwords
    return !NBP.isCommonPassword(password);
}