import java.util.HashMap;
import java.util.Map;
import java.util.Scanner;

public class RomanChallange {

    public static String romanChallange(String str) {
        Map<String, Integer> romanMaps = new HashMap<>();
        romanMaps.put("I",1);
        romanMaps.put("V",5);
        romanMaps.put("X",10);
        romanMaps.put("L",50);
        romanMaps.put("C",100);
        romanMaps.put("D",500);
        String[] parseStr = str.split("");
        int result = 0;

        for (int i = 0; i < parseStr.length; i++) {
            try {
                if(romanMaps.get(parseStr[i]) < romanMaps.get(parseStr[i+1])){
                    result -= romanMaps.get(parseStr[i]);
                }else{
                    result += romanMaps.get(parseStr[i]);
                }
            }catch (Exception e){
                result += romanMaps.get(parseStr[i]);
            }
        }

        return String.valueOf(result);
    }

    public static void main(String[] args) {

        Scanner s = new Scanner(System.in);
        System.out.print(romanChallange(s.nextLine()));
    }
}
